<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Block\Form\Renderer;


use Codilar\DynamicForm\Helper\VariableFieldRenderer;
use Magento\Framework\View\Element\Template;

class CustomHtml extends AbstractRenderer
{
    protected $_template = "Codilar_DynamicForm::form/renderer/custom_html.phtml";
    /**
     * @var VariableFieldRenderer
     */
    private $variableFieldRenderer;

    /**
     * CustomHtml constructor.
     * @param Template\Context $context
     * @param VariableFieldRenderer $variableFieldRenderer
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        VariableFieldRenderer $variableFieldRenderer,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->variableFieldRenderer = $variableFieldRenderer;
    }

    /**
     * @return string
     */
    public function getCustomHtml()
    {
        $element = $this->getElement();
        $html = $element->getCustomHtml();
        $data = [
            'identifier'    =>  $element->getIdentifier(),
            'label'         =>  $element->getLabel(),
            'class'         =>  $element->getClassName(),
            'name'          =>  $element->getName(),
            'id'            =>  $this->getElementId(),
            'value'         =>  $this->getValue(),
            'validation_json'   =>  $this->getValidationString()
        ];
        foreach ($data as &$item) {
            if (is_string($item)) {
                $item = __($item)->render();
            }
        }
        return $this->variableFieldRenderer->render($html, $data);
    }
}