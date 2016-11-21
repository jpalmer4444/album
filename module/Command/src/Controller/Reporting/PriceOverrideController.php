<?php

namespace Command\Controller\Reporting;

use Application\Service\LoggingServiceInterface;
use Command\Query\ORMQuery;
use Doctrine\ORM\EntityManager;
use RuntimeException;
use Zend\Console\Console;
use Zend\Console\Prompt\Char;
use Zend\Console\Prompt\Line;
use Zend\Console\Request;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Description of PriceOverrideController
 *
 * @author jasonpalmer
 */
class PriceOverrideController extends AbstractActionController {

    protected $logger;
    protected $pricingconfig;
    protected $entityManager;

    public function __construct(LoggingServiceInterface $logger, EntityManager $entityManager, $array) {
        $this->logger = $logger;
        $this->pricingconfig = $array;
        $this->entityManager = $entityManager;
    }

    protected function priceoverridereportAction() {

        $this->logger->info("Price Override Report.");

        $request = $this->getRequest();

        $console = Console::getInstance();
        
        // Make sure that we are running in a console and the user has not tricked our
        // application into running this action from a public web server.
        if (!$request instanceof Request) {
            //$this->logger->info(get_class($request));
            throw new RuntimeException('You can only use this action from a console!');
        }else{
            $console->clear();
        }
        
        

        // Get user email from console and check if the user used --verbose or -v flag
        $verbose = $request->getParam('verbose') || $request->getParam('v');

        $from = Line::prompt(
                        'Enter from date (nothing=this morning)?', true, 100
        );

        $fromDate = empty($from) ? date("Y-m-d H:i:s", strval(strtotime('midnight'))) : date("Y-m-d H:i:s", strval(strtotime($from)));

        $to = Line::prompt(
                        'Enter to date (nothing=now)?', true, 100
        );

        $toDate = empty($to) ? date("Y-m-d H:i:s", strval(strtotime('now'))) : date("Y-m-d H:i:s", strval(strtotime($to)));

        if ($verbose) {
            $console->writeLine("<-v|--verbose> Output Enabled.");
        }

        //var_dump($message);

        $rows = $this->entityManager->
                createQuery(ORMQuery::QUERY_ALL_PRICING_OVERRIDES)->
                setParameter("from", $fromDate)->
                setParameter("to", $toDate)->
                getResult();

        $message = "Queried Pricing Reports for date range from: $fromDate to: $toDate and found: " . count(array_keys($rows)) . " rows. Would you like to see them?";
        $answer = Char::prompt(
                        $message, 'yn', true, false, true
        );
        if ($answer == 'y') {
            //$console->write(strval($rows));
            foreach($rows as $key => $value){
                $console->writeLine($value->getProduct()->getProductname() .
                        " | " . $value->getProduct()->getDescription() .
                        " | " . $value->getProduct()->getComment() .
                        " | " . $value->getProduct()->getUom() .
                        " | " . $value->getProduct()->getSku() .
                        " | " . $value->getProduct()->getRetail() .
                        " | " . $value->getOverrideprice() .
                        " | " . strval($value->getCreated()) . 
                        " | " . $value->getCustomerid()->getName() . 
                        " | " . $value->getSalesperson()->getUsername());
            }
        } else {
            $console->write('No worries (less work for me), moving on.');
        }
        //var_dump($rows);

        if (!$verbose) {
            return "Done!\n";
        } else {
            return "Done!\n";
        }
    }

}
