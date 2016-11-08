<?php

namespace Sales\DTO;

use Zend\Form\Form;

class RowPlusItemsPageForm extends Form {

    public function __construct($name = null) {
        // We will ignore the name provided to the constructor
        parent::__construct('addRowItemsForm');

        $this->setAttribute('class', 'form');

        $this->add([
            'name' => 'product',
            'options' => array(
                'label' => 'Product',
                'label_attributes' => array(
                    'for' => 'product'
                ),
            ),
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
                'required' => true,
                'data-msg' => 'Product is required',
                'data-rule-minlength' => '2',
                'data-rule-maxlength' => '255',
                'data-msg-minlength' => 'Use at least 2 chars for Product name',
                'data-msg-maxlength' => 'At most 255 chars for Product name',
            ),
        ]);
        $this->add([
            'name' => 'description',
            'type' => 'text',
            'options' => [
                'label' => 'Description',
                'label_attributes' => array(
                    'for' => 'description'
                ),
            ],
            'attributes' => array(
                'class' => 'form-control',
                'data-rule-maxlength' => '255',
                'data-msg-maxlength' => 'At most 255 chars for Description',
            ),
        ]);
        $this->add([
            'name' => 'comment',
            'type' => 'text',
            'options' => [
                'label' => 'Comment',
                'label_attributes' => array(
                    'for' => 'comment'
                ),
            ],
            'attributes' => array(
                'class' => 'form-control',
                'data-rule-maxlength' => '255',
                'data-msg-maxlength' => 'At most 255 chars for Comment',
            ),
        ]);
        $this->add([
            'name' => 'option',
            'type' => 'text',
            'options' => [
                'label' => 'Option',
                'label_attributes' => array(
                    'for' => 'option'
                ),
            ],
            'attributes' => array(
                'class' => 'form-control',
                'data-rule-maxlength' => '255',
                'data-msg-maxlength' => 'At most 255 chars for Option',
            ),
        ]);
        $this->add([
            'name' => 'qty',
            'type' => 'text',
            'options' => [
                'label' => 'Qty',
                'label_attributes' => array(
                    'for' => 'qty'
                ),
            ],
            'attributes' => array(
                'class' => 'form-control',
                'required' => true,
                'data-msg' => 'Qty is required',
                'data-rule-digits' => true,
                'data-msg-digits' => 'Qty must be a whole number'
            ),
        ]);
        $this->add([
            'name' => 'wholesale',
            'type' => 'text',
            'options' => [
                'label' => 'Wholesale',
                'label_attributes' => array(
                    'for' => 'wholesale'
                ),
            ],
            'attributes' => array(
                'class' => 'form-control',
                'required' => true,
                'data-msg' => 'Wholesale is required',
                'data-rule-number' => true,
                'data-msg-number' => 'Wholesale must be a valid dollar amount'
            ),
        ]);
        $this->add([
            'name' => 'retail',
            'type' => 'text',
            'options' => [
                'label' => 'Retail',
                'label_attributes' => array(
                    'for' => 'retail'
                ),
            ],
            'attributes' => array(
                'class' => 'form-control',
                'required' => true,
                'data-msg' => 'Retail is required',
                'data-rule-number' => true,
                'data-msg-number' => 'Retail must be a valid dollar amount'
            ),
        ]);
        $this->add([
            'name' => 'overrideprice',
            'type' => 'text',
            'options' => [
                'label' => 'Override Price',
                'label_attributes' => array(
                    'for' => 'overrideprice'
                ),
            ],
            'attributes' => array(
                'class' => 'form-control',
                'data-rule-number' => true,
                'data-msg-number' => 'Override Price must be a valid dollar amount'
            ),
        ]);
        $this->add([
            'name' => 'uom',
            'type' => 'text',
            'options' => [
                'label' => 'UOM',
                'label_attributes' => array(
                    'for' => 'uom'
                ),
            ],
            'attributes' => array(
                'class' => 'form-control',
                'required' => true,
                'data-msg' => 'UOM is required',
                'data-rule-minlength' => '2',
                'data-rule-maxlength' => '100',
                'data-msg-minlength' => 'Use at least 2 chars for UOM',
                'data-msg-maxlength' => 'At most 100 chars for UOM',
            ),
        ]);
        $this->add([
            'name' => 'sku',
            'type' => 'text',
            'options' => [
                'label' => 'SKU',
                'label_attributes' => array(
                    'for' => 'sku'
                ),
            ],
            'attributes' => array(
                'class' => 'form-control',
                'required' => true,
                'data-msg' => 'SKU is required',
                'data-rule-digits' => true,
                'data-msg-digits' => 'SKU must be a valid number'
            ),
        ]);
        $this->add([
            'name' => 'status',
            'type' => 'checkbox',
            'options' => [
                'label' => 'Status',
                'label_attributes' => array(
                    'for' => 'status'
                ),
            ],
            'attributes' => array(
                'class' => 'form-control',
                'checked' => 'checked',
            ),
        ]);
        $this->add([
            'name' => 'saturdayenabled',
            'type' => 'checkbox',
            'options' => [
                'label' => 'Saturday Enabled',
                'label_attributes' => array(
                    'for' => 'saturdayenabled'
                ),
            ],
            'attributes' => array(
                'class' => 'form-control'
            ),
        ]);
    }

}
