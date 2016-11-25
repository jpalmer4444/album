<?php

/**
 */

namespace Sales\Controller;

use Doctrine\ORM\Query\Expr\From;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\ORM\Query\Expr\Select;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DateTime;

class UsersController extends AbstractActionController {

    const ID = "jpalmer";
    const PASSWORD = "goodbass";
    const OBJECT = "customers";
    const QUERY_SALESPERSON_BY_ID = "SELECT user FROM DataAccess\FFM\Entity\User user WHERE user.sales_attr_id = :sales_attr_id AND user.salespersonname = :salespersonname";

    protected $restService;
    protected $logger;
    protected $myauthstorage;
    protected $pricingconfig;
    protected $userrepository;
    protected $customerrepository;
    protected $qb;

    public function __construct($container) {
        $this->restService = $container->get('RestService');
        $this->logger = $container->get('LoggingService');
        $this->myauthstorage = $container->get('Login\Model\MyAuthStorage');
        $this->pricingconfig = $container->get('config')['pricing_config'];
        $this->userrepository = $container->get('FFMEntityManager')->
                getEntityManager()->
                getRepository('DataAccess\FFM\Entity\User');
        $this->customerrepository = $container->get('FFMEntityManager')->
                getEntityManager()->
                getRepository('DataAccess\FFM\Entity\Customer');
        $this->qb = $container->get('FFMEntityManager')->
                        getEntityManager()->createQueryBuilder();
    }

    public function indexAction() {
        //http://svc.ffmalpha.com/bySKU.php?id=jpalmer&pw=goodbass&object=customers&salespersonid=183
        $requestedsalespersonid = $this->params()->fromQuery('salespersonid');
        $requestedsalespersonname = $this->params()->fromQuery('salesperson');
        if (isset($requestedsalespersonid) && isset($requestedsalespersonname) && $this->myauthstorage->admin()) {
            $salespersonid = $requestedsalespersonid;
            //lookup salesperson by id and set in Session
            //now lookup the Salesperson they are impersonating...
            $salesperson = $this->userrepository->findBySalesPersonNameAndSalespersonId($requestedsalespersonid, $requestedsalespersonname);
            //we have multiple salespersons here possibly - so match it to logged in username
            $this->myauthstorage->addSalespersonInPlay($salesperson[0]);
        } else if ($this->myauthstorage->admin() && !empty($this->myauthstorage->getSalespersonInPlay())) {
            $salespersonid = $this->myauthstorage->getSalespersonInPlay()->getSales_attr_id();
        } else {
            $salespersonid = $this->myauthstorage->getUser()->getSales_attr_id();
        }

        $this->logger->info('Retrieving Salespeople. ID: ' . $salespersonid);

        $params = [
            "id" => $this->pricingconfig['by_sku_userid'],
            "pw" => $this->pricingconfig['by_sku_password'],
            "object" => $this->pricingconfig['by_sku_object_users_controller'],
            "salespersonid" => $salespersonid
        ];

        $url = $this->pricingconfig['by_sku_base_url'];
        $method = $this->pricingconfig['by_sku_method'];

        $json = $this->rest($url, $method, $params);
        $this->logger->info('Retrieved #' . count($json) . ' ' . $this->pricingconfig['by_sku_object_users_controller'] . '.');

        //now lookup these items in the DB and update if there are discrepancies
        $this->qb->add('select', new Select(array('u')))
                ->add('from', new From('DataAccess\FFM\Entity\Customer', 'u'));
        $arr = [];
        foreach ($json['customers'] as $customer) {
            //$this->logger->info(json_encode($customer));
            $arr [] = $this->qb->expr()->eq('u.email', "'" . utf8_encode($customer['email']) . "'");
        }

        $this->qb->add('where', $this->qb->expr()->orX(
                                implode(" OR ", $arr)
                ))
                ->add('orderBy', new OrderBy('u.name', 'ASC'));

        $query = $this->qb->getQuery();
        $dbcustomers = $query->getResult();
        $this->logger->info('Found ' . count($dbcustomers) . ' users in db.');

        $inDb = count($dbcustomers);
        $inSvc = count($json['customers']);

        if ($inDb < $inSvc) {

            //remove every matching row in DB and rewrite them all to guarantee we have latest data
            //in theory this should flush everything out and keep records up-to-date over time.
            $some = false;
            foreach ($json['customers'] as $customer) {

                //lookup item with id
                $cdb = $this->customerrepository->find($customer['id']);
                if (!empty($cdb)) {

                    //update existing record
                    $cdb->setEmail($customer['email']);
                    $cdb->setName($customer['name']);
                    $cdb->setCompany($customer['company']);
                    $cdb->setUpdated(new DateTime());
                    $this->customerrepository->merge($cdb);
                    $some = true;
                } else {
                    //insert record because it doesn't exist.
                    $cdb = new \DataAccess\FFM\Entity\Customer();
                    $cdb->setId($customer['id']);
                    $cdb->setEmail($customer['email']);
                    $cdb->setName($customer['name']);
                    $cdb->setCompany($customer['company']);
                    $cdb->setCreated(new DateTime());
                    $this->customerrepository->persist($cdb);
                    $some = true;
                }
            }

            if ($some) {
                $this->userrepository->flush();
            }
        }
        
        //use usort to apply
        usort($json['customers'], function(array $a, array $b) {
            if (strtoupper($a['company']) < strtoupper($b['company'])) {
                return -1;
            } else if (strtoupper($a['company']) > strtoupper($b['company'])) {
                return 1;
            } else {
                return 0;
            }
        });

        return new ViewModel(array(
            "json" => $json
        ));
    }

    public function rest($url, $method = "GET", $params = []) {
        return $this->restService->rest($url, $method, $params);
    }

}
