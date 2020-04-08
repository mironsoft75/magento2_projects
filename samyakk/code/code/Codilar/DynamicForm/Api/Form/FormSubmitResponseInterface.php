<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Api\Form;


interface FormSubmitResponseInterface
{
    /**
     * @return boolean
     */
    public function getStatus();

    /**
     * @param boolean $status
     * @return \Codilar\DynamicForm\Api\Form\FormSubmitResponseInterface
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param string $message
     * @return \Codilar\DynamicForm\Api\Form\FormSubmitResponseInterface
     */
    public function setMessage($message);
}