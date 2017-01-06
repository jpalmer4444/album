<?php

namespace DataAccess\FFM\Entity;

use Application\Utility\Logger;
use DataAccess\FFM\Entity\PostFormBinderInterface;
use Zend\Form\Form;

/**
 * Description of FFMEntity
 *
 * @author jasonpalmer
 */
class PostFormBinder implements PostFormBinderInterface {

    protected $logger;

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
        Logger::info("PostFormBinder", __LINE__, $m);
    }

}
