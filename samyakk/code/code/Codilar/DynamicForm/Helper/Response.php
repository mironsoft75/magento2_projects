<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Helper;

use Codilar\DynamicForm\Api\Data\Form\ResponseInterface;
use Codilar\DynamicForm\Model\ResourceModel\Form\CollectionFactory as FormCollectionFactory;
use Codilar\DynamicForm\Model\ResourceModel\Form\Response as ResponseResource;
use Codilar\DynamicForm\Model\ResourceModel\Form\Response\Field as ResponseFieldResource;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;

class Response
{
    /**
     * @var FormCollectionFactory
     */
    private $formCollectionFactory;

    /**
     * Response constructor.
     * @param FormCollectionFactory $formCollectionFactory
     */
    public function __construct(
        FormCollectionFactory $formCollectionFactory
    )
    {
        $this->formCollectionFactory = $formCollectionFactory;
    }

    /**
     * @param int $formId
     * @param string $incrementId
     * @param string $orderIdField
     * @param bool $force
     * @return DataObject
     * @throws NoSuchEntityException
     */
    public function getFilledResponse($formId, $incrementId, $orderIdField = 'order_id', $force = false)
    {
        if (!$force) {
            throw new NoSuchEntityException(__("The form fill logic is currently disabled"));
        }
        $collection = $this->formCollectionFactory->create();
        $collection->addFieldToFilter("main_table.id", $formId);
        $select = $collection->getSelect();
        $select->reset(\Zend_Db_Select::COLUMNS)->columns(['id', 'title']);
        $select->join(
            ["response" => ResponseResource::TABLE_NAME],
            "main_table.id = response.form_id",
            ['form_response_id' => 'id']
        )->join(
            ["response_field" => ResponseFieldResource::TABLE_NAME],
            $select->getConnection()->quoteInto(
                "response.id = response_field.form_response_id AND response_field.name = ?",
                $orderIdField
            ),
            []
        )->where(
            $select->getConnection()->quoteInto(
                'response_field.value = ?',
                $incrementId
            )
        )->limit(1);
        $response = $collection->getFirstItem();
        if (!$response->getData('id')) {
            throw NoSuchEntityException::doubleField('form_id', $formId, 'increment_id', $incrementId);
        }
        return $response;
    }
}