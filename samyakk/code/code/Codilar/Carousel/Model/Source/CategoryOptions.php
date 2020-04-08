<?php

/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Model\Source;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class CategoryOptions implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * CarouselOptions constructor.
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    )
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function toOptionArray()
    {
        $collection = $this->getCollection();
        $options = [];
        if ($collection->getSize()) {
            /** @var Category $item */
            foreach ($collection as $item) {
                $options[] = [
                    'value' => $item->getId(),
                    'label' => $item->getName()
                ];
            }
        }
        return $options;
    }

    /**
     * @return Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getCollection()
    {
        return $this->collectionFactory->create()->addAttributeToSelect(["entity_id", "name"])->addAttributeToFilter("is_active", 1);
    }
}