<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Api\Form;


use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface ElementRepositoryInterface
{
    /**
     * @param string $value
     * @param string|null $field
     * @return \Codilar\DynamicForm\Api\Data\Form\ElementInterface
     * @throws NoSuchEntityException
     */
    public function load($value, $field = null);

    /**
     * @return \Codilar\DynamicForm\Api\Data\Form\ElementInterface
     */
    public function create();

    /**
     * @param \Codilar\DynamicForm\Model\Form\Element $model
     * @return \Codilar\DynamicForm\Api\Data\Form\ElementInterface
     */
    public function getDataModel(\Codilar\DynamicForm\Model\Form\Element $model);

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\ElementInterface $formElement
     * @return $this
     * @throws CouldNotDeleteException
     */
    public function delete(\Codilar\DynamicForm\Api\Data\Form\ElementInterface $formElement);

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\ElementInterface $formElement
     * @return \Codilar\DynamicForm\Api\Data\Form\ElementInterface
     * @throws CouldNotSaveException
     */
    public function save(\Codilar\DynamicForm\Api\Data\Form\ElementInterface $formElement);

    /**
     * @param \Magento\Framework\Api\SearchCriteria $searchCriteria
     * @return \Codilar\DynamicForm\Api\Data\Form\ElementSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteria $searchCriteria);
}