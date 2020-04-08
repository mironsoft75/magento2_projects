<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Api;


use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface FormRepositoryInterface
{
    /**
     * @param string $value
     * @param string|null $field
     * @return \Codilar\DynamicForm\Api\Data\FormInterface
     * @throws NoSuchEntityException
     */
    public function load($value, $field = null);

    /**
     * @return \Codilar\DynamicForm\Api\Data\FormInterface
     */
    public function create();

    /**
     * @param \Codilar\DynamicForm\Model\Form $model
     * @return \Codilar\DynamicForm\Api\Data\FormInterface
     */
    public function getDataModel(\Codilar\DynamicForm\Model\Form $model);

    /**
     * @param \Codilar\DynamicForm\Api\Data\FormInterface $form
     * @return $this
     * @throws CouldNotDeleteException
     */
    public function delete(\Codilar\DynamicForm\Api\Data\FormInterface $form);

    /**
     * @param \Codilar\DynamicForm\Api\Data\FormInterface $form
     * @return \Codilar\DynamicForm\Api\Data\FormInterface
     * @throws CouldNotSaveException
     */
    public function save(\Codilar\DynamicForm\Api\Data\FormInterface $form);

    /**
     * @param \Magento\Framework\Api\SearchCriteria $searchCriteria
     * @return \Codilar\DynamicForm\Api\Data\FormSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteria $searchCriteria);
}