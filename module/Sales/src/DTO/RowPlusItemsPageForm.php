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
            'name' => 'overrideprice',
            'type' => 'text',
            'options' => [
                'label' => 'Override Price',
                'label_attributes' => array(
                    'for' => 'overrideprice'
                ),
            ],
            'attributes' => array(
                'class' => 'form-control ffm-money',
                'data-msg-money' => 'Override Price must be a valid dollar amount < 10,000.00'
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
                'class' => 'form-control ffm-sku',
                'data-msg-sku' => 'Please enter a valid SKU'
            ),
        ]);
    }

}
