<?php

namespace DataAccess\FFM\Entity;

use Zend\Form\Form;

/**
 * Description of FFMEntity
 *
 * @author jasonpalmer
 */
class PostFormBinder implements PostFormBinderInterface {
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

    public function bind(Form $form, $postData) {

        //need to know if this Element
        //iterate the form and inject properties using reflection into model
        foreach ($form->getData() as $formKey => $formValue) {
            if (method_exists($this, $formKey)) {
                $keys = array_keys($form->get($formKey)->getAttributes());
                $isPriceType = preg_grep("[data-rule-digits|data-rule-number]", $keys);
                $reflectionMethod = new ReflectionMethod(get_parent_class($this), $formKey);
                $postData[$formKey] = $formValue;
                if ($isPriceType) {
                    $int = filter_var($formValue, FILTER_SANITIZE_NUMBER_INT);
                    $reflectionMethod->invoke($this, $int);
                } else {
                    $reflectionMethod->invoke($this, $formValue);
                }
            }
        }
        return $postData;
    }

}
