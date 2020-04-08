<?php

/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Model\Source;

use Magento\Cms\Model\Page;
use Magento\Cms\Model\ResourceModel\Page\Collection;
use Magento\Cms\Model\ResourceModel\Page\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

class CmsOptions implements OptionSourceInterface
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
            /** @var Page $item */
            foreach ($collection as $item) {
                $options[] = [
                    'value' => $item->getIdentifier(),
                    'label' => $item->getTitle()
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
        return $this->collectionFactory->create()->addFieldToFilter("is_active", 1);
    }
}