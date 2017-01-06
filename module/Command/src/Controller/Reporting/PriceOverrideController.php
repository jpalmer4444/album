<?php

namespace Command\Controller\Reporting;

use Application\Service\LoggingServiceInterface;
use Application\Utility\Logger;
use DateTime;
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
        $this->pricingoverridereportrepository = $this->entityManager->
                getRepository('DataAccess\FFM\Entity\PricingOverrideReport');
    }

    protected function priceoverridereportAction() {

        Logger::info("PriceOverrideController", __LINE__, "Price Override Report.");

        $request = $this->getRequest();

        $console = Console::getInstance();
        
        // Make sure that we are running in a console and the user has not tricked our
        // application into running this action from a public web server.
        if (!$request instanceof Request) {
            //Logger::info("PriceOverrideController", __LINE__, get_class($request));
            throw new RuntimeException('You can only use this action from a console!');
        }else{
            $console->clear();
        }
        
        

        // Get user email from console and check if the user used --verbose or -v flag
        $verbose = $request->getParam('verbose') || $request->getParam('v');

        $from = Line::prompt(
                        'Enter from date (nothing=this morning)?', true, 100
        );

        $fromDate = empty($from) ? new DateTime(date("Y-m-d H:i:s", strval(strtotime('midnight')))) : new DateTime(date("Y-m-d H:i:s", strval(strtotime($from))));

        $to = Line::prompt(
                        'Enter to date (nothing=now)?', true, 100
        );

        $toDate = empty($to) ? new DateTime(date("Y-m-d H:i:s", strval(strtotime('now')))) : new DateTime(date("Y-m-d H:i:s", strval(strtotime($to))));

        if ($verbose) {
            $console->writeLine("<-v|--verbose> Output Enabled.");
        }

        //var_dump($message);

        $rows = $this->pricingoverridereportrepository->reportBetween($fromDate, $toDate);

        $message = "Queried Pricing Reports for date range from: " . $fromDate->format('Y-m-d H:i:s') . " to: " . $toDate->format('Y-m-d H:i:s') . "and found: " . count(array_keys($rows)) . " rows. Would you like to see them?";
        $answer = Char::prompt(
                        $message, 'yn', true, false, true
        );
        if ($answer == 'y') {
            //$console->write(strval($rows));
            
            foreach($rows as $value){
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
