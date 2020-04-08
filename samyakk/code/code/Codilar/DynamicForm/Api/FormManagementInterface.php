<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Api;


interface FormManagementInterface
{

    /**
     * @param int $id
     * @return \Codilar\DynamicForm\Api\Data\FormInterface
     */
    public function renderById($id);

    /**
     * @param string $identifier
     * @return \Codilar\DynamicForm\Api\Data\FormInterface
     */
    public function renderByIdentifier($identifier);

    /**
     * @param int $id
     * @param \Codilar\DynamicForm\Api\Form\DynamicFormFieldDataInterface[] $data
     * @return \Codilar\DynamicForm\Api\Form\FormSubmitResponseInterface
     */
    public function submit($id, $data);

    /**
     * @param int $productId
     * @return \Codilar\DynamicForm\Api\Data\FormItemInterface[]
     */
    public function getFormsByProduct($productId);
}