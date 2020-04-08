<?php
/**
 *
 * @package     codilar
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        https://www.codilar.com/
 */

namespace Codilar\AdminLogs\Block\Adminhtml\System\Form\Field;

class Select extends \Magento\Framework\View\Element\Html\Select
{
    protected function _toHtml()
    {
        $this->setName($this->getInputName());
        $this->setClass('select');
        return trim(preg_replace('/\s+/', ' ', parent::_toHtml()));
    }
}