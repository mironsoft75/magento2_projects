<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Api\Form;


use Codilar\DynamicForm\Exception\CouldNotNotifyException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface ResponseRepositoryInterface
{
    /**
     * @param string $value
     * @param string|null $field
     * @return \Codilar\DynamicForm\Api\Data\Form\ResponseInterface
     * @throws NoSuchEntityException
     */
    public function load($value, $field = null);

    /**
     * @return \Codilar\DynamicForm\Api\Data\Form\ResponseInterface
     */
    public function create();

    /**
     * @param \Codilar\DynamicForm\Model\Form\Response $model
     * @return \Codilar\DynamicForm\Api\Data\Form\ResponseInterface
     */
    public function getDataModel(\Codilar\DynamicForm\Model\Form\Response $model);

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\ResponseInterface $formResponse
     * @return $this
     * @throws CouldNotDeleteException
     */
    public function delete(\Codilar\DynamicForm\Api\Data\Form\ResponseInterface $formResponse);

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\ResponseInterface $formResponse
     * @param bool $notify
     * @return \Codilar\DynamicForm\Api\Data\Form\ResponseInterface
     * @throws CouldNotSaveException
     */
    public function save(\Codilar\DynamicForm\Api\Data\Form\ResponseInterface $formResponse, $notify = true);

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\ResponseInterface $formResponse
     * @return $this
     * @throws CouldNotNotifyException
     * @throws NoSuchEntityException
     */
    public function notify(\Codilar\DynamicForm\Api\Data\Form\ResponseInterface $formResponse);

    /**
     * @param \Magento\Framework\Api\SearchCriteria $searchCriteria
     * @return \Codilar\DynamicForm\Api\Data\Form\ResponseSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteria $searchCriteria);
}