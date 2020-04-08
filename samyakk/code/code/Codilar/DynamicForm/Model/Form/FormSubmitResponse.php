<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Model\Form;


use Codilar\DynamicForm\Api\Form\FormSubmitResponseInterface;
use Magento\Framework\DataObject;

class FormSubmitResponse extends DataObject implements FormSubmitResponseInterface
{

    /**
     * @return boolean
     */
    public function getStatus()
    {
        return $this->getData("status");
    }

    /**
     * @param boolean $status
     * @return \Codilar\DynamicForm\Api\Form\FormSubmitResponseInterface
     */
    public function setStatus($status)
    {
        return $this->setData("status", $status);
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->getData("message");
    }

    /**
     * @param string $message
     * @return \Codilar\DynamicForm\Api\Form\FormSubmitResponseInterface
     */
    public function setMessage($message)
    {
        return $this->setData("message", $message);
    }
}