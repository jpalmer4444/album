<?php

namespace Sales\Factory;

use Sales\DTO\RowPlusItemsPageForm;
use Sales\Filter\RowPlusItemsPageInputFilter;
use Zend\Hydrator\ObjectProperty;
use Zend\ServiceManager\ServiceManager;

/**
 * Description of RowPlusItemsPageFormFactory
 *
 * @author jasonpalmer
 */
class RowPlusItemsPageFormFactory {
    
    public function __invoke(ServiceManager $container) {
        $form = new RowPlusItemsPageForm();
                    $form->setInputFilter(new RowPlusItemsPageInputFilter());
                    $form->setHydrator(new ObjectProperty());
                    return $form;
    }
    
}
