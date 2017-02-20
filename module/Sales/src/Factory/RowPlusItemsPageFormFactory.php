<?php

namespace Sales\Factory;

use Interop\Container\ContainerInterface;
use Sales\DTO\RowPlusItemsPageForm;
use Sales\Filter\RowPlusItemsPageInputFilter;
use Zend\Hydrator\ObjectProperty;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of RowPlusItemsPageFormFactory
 *
 * @author jasonpalmer
 */
class RowPlusItemsPageFormFactory implements FactoryInterface{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL) {
        $form = new RowPlusItemsPageForm();
                    $form->setInputFilter(new RowPlusItemsPageInputFilter());
                    $form->setHydrator(new ObjectProperty());
                    return $form;
    }
    
}
