<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Block\Form\Renderer;


class Text extends AbstractRenderer
{

    protected $_template = "Codilar_DynamicForm::form/renderer/text.phtml";

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getData('type');
    }
}