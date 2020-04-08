<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Block\Form\Renderer;


use Codilar\DynamicForm\Api\Data\Form\ElementInterface;
use Magento\Framework\View\Element\Template;

class AbstractRenderer extends Template
{

    protected $elementId;

    /**
     * @return ElementInterface
     */
    public function getElement()
    {
        return $this->getData('element');
    }

    /**
     * @return string
     */
    public function getElementId()
    {
        if (!$this->elementId)
        {
            $element = $this->getElement();
            $this->elementId = $element->getIdentifier() . '-' . $element->getId() . '-' . uniqid();
        }
        return $this->elementId;
    }

    /**
     * @return string
     */
    public function getValidationString()
    {
        $validations = [];
        foreach ($this->getElement()->getValidation() as $validationOption) {
            $value = explode(',', $validationOption->getValue());
            foreach ($value as &$datum) {
                if ($datum === 'true') {
                    $datum = true;
                } else if ($datum === 'false') {
                    $datum = false;
                } else if (is_numeric($datum)) {
                    $datum = (double)$datum;
                }
            }
            if (count($value) === 1) {
                $value = $value[0];
            }
            $validations[$validationOption->getLabel()] = $value;
        }
        return \json_encode($validations);
    }

    /**
     * @return string|null
     */
    public function getValue()
    {
        return $this->getRequest()->getParam($this->getElement()->getName(), null);
    }
}