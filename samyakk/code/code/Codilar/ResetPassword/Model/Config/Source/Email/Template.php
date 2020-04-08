<?php
/**
 * @package     inq2
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\ResetPassword\Model\Config\Source\Email;


class Template extends \Magento\Config\Model\Config\Source\Email\Template
{
    public function toOptionArray()
    {
        return array_merge(array(array('value' => -1, 'label' => '--None--')),parent::toOptionArray());
    }
}