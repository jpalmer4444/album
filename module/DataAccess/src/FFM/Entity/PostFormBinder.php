<?php

namespace DataAccess\FFM\Entity;

use ReflectionMethod;
use Zend\Form\Form;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

/**
 * Description of FFMEntity
 *
 * @author jasonpalmer
 */
class PostFormBinder implements PostFormBinderInterface {

    protected $logger;

    /*
     * $jsonModelArr["qty"] = empty($form->getData()['qty']) ? '' : $form->getData()['qty'];
      if (array_key_exists("qty", $jsonModelArr)) {
      $record->setQty($jsonModelArr["qty"]);
      }
      $jsonModelArr["wholesale"] = empty($form->getData()['wholesale']) ? '' : $form->getData()['wholesale'];
      if (array_key_exists("wholesale", $jsonModelArr)) {
      $int = filter_var($jsonModelArr["wholesale"], FILTER_SANITIZE_NUMBER_INT);
      $record->setWholesale($int);
      }
     */

    public function bind(Form $form, $postData, $instance) {
        
        //need to know if this Element
        //iterate the form and inject properties using reflection into model
        foreach ($form->getData() as $formKey => $formValue) {
            $this->log("PostFormBinder binding key: " . $formKey);
            $this->log("PostFormBinder binding value: " . $formValue);
            $accessor = "set" . ucfirst($formKey);
            if (method_exists($instance, $accessor)) {
                $this->log("PostFormBinder method found: " . $accessor);
                $keys = array_keys($form->get($formKey)->getAttributes());
                $isPriceType = preg_grep("[data-rule-digits|data-rule-number]", $keys);
                $reflectionMethod = new ReflectionMethod($instance, $accessor);
                $postData[$formKey] = $formValue;
                if ($isPriceType) {
                    $int = filter_var($formValue, FILTER_SANITIZE_NUMBER_INT);
                    $reflectionMethod->invoke($instance, $int);
                } else {
                    $reflectionMethod->invoke($instance, $formValue);
                }
            }
        }
        return $postData;
    }

    private function log($m) {
        if ($this->hasLogger()) {
            $this->logger->info($m);
        } else {
            $writer = new Stream('php://stderr');
            $logger = new Logger();
            $logger->addWriter($writer);
            $logger->error("PostFormBinder - please configure logger in DataModel by calling Parent::setLogger()");
        }
    }

    private function hasLogger() {
        return !empty($this->logger);
    }

    public function setLogger($logger) {
        $this->logger = $logger;
    }

}
