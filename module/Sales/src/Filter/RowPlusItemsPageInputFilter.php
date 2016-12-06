<?php

namespace Sales\Filter;

use Zend\InputFilter\InputFilter;

/**
 * Description of RowPlusItemsPageInputFilter
 *
 * @author jasonpalmer
 */
class RowPlusItemsPageInputFilter extends InputFilter{
    
    
    public function init()
    {
        $this->add(array(
            'name' => 'product',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'not_empty',
                ),
                array(
                    'name' => 'string_length',
                    'options' => array(
                        'min' => 3,
                        'max' => 255
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'required' => false,
            'validators' => array(
                array(
                    'name' => 'string_length',
                    'options' => array(
                        'min' => 0,
                        'max' => 255
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => 'comment',
            'required' => false,
            'validators' => array(
                array(
                    'name' => 'string_length',
                    'options' => array(
                        'min' => 0,
                        'max' => 255
                    ),
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'option',
            'required' => false,
            'validators' => array(
                array(
                    'name' => 'string_length',
                    'options' => array(
                        'min' => 0,
                        'max' => 255
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => 'qty',
            'required' => true,
            'filters' => array(
                array('name' => 'Int'),
            ),
            'validators' => array(
                array(
                    'name' => 'not_empty',
                ),
                array(
                    'name' => 'Between',
                    'options' => array(
                        'min' => 1,
                        'max' => 100000,
                    ),
                ),
            ),
                )
        );
        
        $this->add(array(
            'name' => 'wholesale',
            'required' => true,
            'filters' => array(
                array('name' => 'Float'),
            ),
            'validators' => array(
                array(
                    'name' => 'not_empty',
                ),
                array(
                    'name' => 'Between',
                    'options' => array(
                        'min' => .01
                    ),
                ),
            ),
                )
        );
        
        $this->add(array(
            'name' => 'retail',
            'required' => true,
            'filters' => array(
                array('name' => 'Float'),
            ),
            'validators' => array(
                array(
                    'name' => 'not_empty',
                ),
                array(
                    'name' => 'Between',
                    'options' => array(
                        'min' => .01
                    ),
                ),
            ),
                )
        );
        
        $this->add(array(
            'name' => 'overrideprice',
            'required' => false,
            'filters' => array(
                array('name' => 'Float'),
            ),
            'validators' => array(
                array(
                    'name' => 'Between',
                    'options' => array(
                        'min' => .01,
                        'max' => 9999999999
                    ),
                ),
            ),
                )
        );
        
        $this->add(array(
            'name' => 'uom',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'not_empty',
                ),
                array(
                    'name' => 'string_length',
                    'options' => array(
                        'min' => 1,
                        'max' => 100
                    ),
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'sku',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'not_empty',
                ),
                array(
                    'name' => 'string_length',
                    'options' => array(
                        'min' => 1,
                        'max' => 25
                    ),
                ),
            ),
        ));
    }
    
}
