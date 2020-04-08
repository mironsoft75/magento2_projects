<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Model\Config\Source;


use Codilar\DynamicForm\Api\Data\FormInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Codilar\DynamicForm\Model\ResourceModel\Form\CollectionFactory;
use Codilar\DynamicForm\Model\ResourceModel\Form\Collection as FormCollection;

class Forms extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * Forms constructor.
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
       CollectionFactory $collectionFactory
   )
   {
       $this->collectionFactory = $collectionFactory;
   }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $response = [];
        $collection = $this->getCollection();
        if ($collection) {
            /** @var FormInterface $item */
            foreach ($collection as $item) {
                $response[] = [
                    "value" => $item->getId(),
                    "label" => $item->getTitle()
                ];
            }
        }
        return $response;
    }


    /**
     * @return bool|FormCollection
     */
    public function getCollection()
    {
        /** @var FormCollection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter(FormInterface::IS_ACTIVE, 1);
        if ($collection->getSize()) {
            return $collection;
        }
        return false;
    }

    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions()
    {
        return $this->toOptionArray();
    }
}