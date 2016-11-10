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

        $jsonModelArr["qty"] = empty($form->getData()['qty']) ? '' : $form->getData()['qty'];
        if (array_key_exists("qty", $jsonModelArr)) {
            $this->setQty($jsonModelArr["qty"]);
        }
        $jsonModelArr["wholesale"] = empty($form->getData()['wholesale']) ? '' : $form->getData()['wholesale'];
        if (array_key_exists("wholesale", $jsonModelArr)) {
            $int = filter_var($jsonModelArr["wholesale"], FILTER_SANITIZE_NUMBER_INT);
            $this->setWholesale($int);
        }

        foreach ($postData as $key => $value) {

            //get element attribute keys and check if this is a price item
            $keys = array_keys($form->get($key)->getAttributes());
            $isPriceType = preg_grep("[data-rule-digits|data-rule-number]", $keys);

            if ($isPriceType) {
                //$int = filter_var($jsonModelArr["wholesale"], FILTER_SANITIZE_NUMBER_INT);
                    //$record->setWholesale($int);
            } else {
                
            }
        }
    }

}
