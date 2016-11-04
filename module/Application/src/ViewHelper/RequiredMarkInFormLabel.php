<?php

namespace Application\ViewHelper;

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormLabel as OriginalFormLabel;

class RequiredMarkInFormLabel extends OriginalFormLabel
{
    public function __invoke(ElementInterface $element = null, $labelContent = null, $position = null)
    {

        // Set $required to a default of true | existing elements required-value
        $required = ($element->hasAttribute('required') ? true : false);
        $labelContent = $element->getLabel();
        if (true === $required) {
            $labelContent = '<i>' . $element->getLabel() . '</i>&nbsp;<span style="color:red;">*</span>';
        }

        return $this->openTag() . $labelContent . $this->closeTag();
    }
}
