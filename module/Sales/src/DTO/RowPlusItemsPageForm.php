<?php

namespace Sales\DTO;

use Zend\Form\Form;

class RowPlusItemsPageForm extends Form {

    public function __construct($name = null) {
        // We will ignore the name provided to the constructor
        parent::__construct('addRowItemsForm');

        $this->add([
            'name' => 'product',
            'options' => array(
                'label' => 'Product',
            ),
            'type' => 'text',
        ]);
        $this->add([
            'name' => 'description',
            'type' => 'text',
            'options' => [
                'label' => 'Description',
            ],
        ]);
        $this->add([
            'name' => 'comment',
            'type' => 'text',
            'options' => [
                'label' => 'Comment',
            ],
        ]);
        $this->add([
            'name' => 'option',
            'type' => 'text',
            'options' => [
                'label' => 'Option',
            ],
        ]);
        $this->add([
            'name' => 'qty',
            'type' => 'text',
            'options' => [
                'label' => 'Qty',
            ],
        ]);
        $this->add([
            'name' => 'wholesale',
            'type' => 'text',
            'options' => [
                'label' => 'Wholesale',
            ],
        ]);
        $this->add([
            'name' => 'retail',
            'type' => 'text',
            'options' => [
                'label' => 'Retail',
            ],
        ]);
        $this->add([
            'name' => 'overrideprice',
            'type' => 'text',
            'options' => [
                'label' => 'Override Price',
            ],
        ]);
        $this->add([
            'name' => 'uom',
            'type' => 'text',
            'options' => [
                'label' => 'UOM',
            ],
        ]);
        $this->add([
            'name' => 'sku',
            'type' => 'text',
            'options' => [
                'label' => 'SKU',
            ],
        ]);
        $this->add([
            'name' => 'status',
            'type' => 'checkbox',
            'options' => [
                'label' => 'Status',
            ]
        ]);
        $this->add([
            'name' => 'saturdayenabled',
            'type' => 'checkbox',
            'options' => [
                'label' => 'Saturday Enabled',
            ],
        ]);
    }
}
