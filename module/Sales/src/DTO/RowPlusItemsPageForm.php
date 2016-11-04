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
            ),
            'type' => 'text',
            'attributes' => array(
                'class' => 'form-control',
                'required' => true
            ),
        ]);
        $this->add([
            'name' => 'description',
            'type' => 'text',
            'options' => [
                'label' => 'Description',
            ],
            'attributes' => array(
                'class' => 'form-control'
            ),
        ]);
        $this->add([
            'name' => 'comment',
            'type' => 'text',
            'options' => [
                'label' => 'Comment',
            ],
            'attributes' => array(
                'class' => 'form-control'
            ),
        ]);
        $this->add([
            'name' => 'option',
            'type' => 'text',
            'options' => [
                'label' => 'Option',
            ],
            'attributes' => array(
                'class' => 'form-control'
            ),
        ]);
        $this->add([
            'name' => 'qty',
            'type' => 'text',
            'options' => [
                'label' => 'Qty',
            ],
            'attributes' => array(
                'class' => 'form-control',
                'required' => true
            ),
        ]);
        $this->add([
            'name' => 'wholesale',
            'type' => 'text',
            'options' => [
                'label' => 'Wholesale',
            ],
            'attributes' => array(
                'class' => 'form-control',
                'required' => true
            ),
        ]);
        $this->add([
            'name' => 'retail',
            'type' => 'text',
            'options' => [
                'label' => 'Retail',
            ],
            'attributes' => array(
                'class' => 'form-control',
                'required' => true
            ),
        ]);
        $this->add([
            'name' => 'overrideprice',
            'type' => 'text',
            'options' => [
                'label' => 'Override Price',
            ],
            'attributes' => array(
                'class' => 'form-control'
            ),
        ]);
        $this->add([
            'name' => 'uom',
            'type' => 'text',
            'options' => [
                'label' => 'UOM',
            ],
            'attributes' => array(
                'class' => 'form-control',
                'required' => true
            ),
        ]);
        $this->add([
            'name' => 'sku',
            'type' => 'text',
            'options' => [
                'label' => 'SKU',
            ],
            'attributes' => array(
                'class' => 'form-control',
                'required' => true
            ),
        ]);
        $this->add([
            'name' => 'status',
            'type' => 'checkbox',
            'options' => [
                'label' => 'Status',
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
            ],
            'attributes' => array(
                'class' => 'form-control'
            ),
        ]);
    }
}
