<?php

/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Model\Source;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class ProductOptions implements OptionSourceInterface
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
     */
    public function toOptionArray()
    {
        $collection = $this->getCollection();
        $options = [];
        if ($collection->getSize()) {
            /** @var Product $item */
            foreach ($collection as $item) {
                $options[] = [
                    'value' => $item->getSku(),
                    'label' => $item->getName(). " - (".$item->getSku().")"
                ];
            }
        }
        return $options;
    }

    /**
     * @return Collection
     */
    protected function getCollection()
    {
        return $this->collectionFactory->create()->addAttributeToSelect(["entity_id", "name", "sku"])->addAttributeToFilter("status", 1);
    }
}