<?php

/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Model\Source;

use Codilar\Carousel\Api\CarouselRepositoryInterface;
use Codilar\Carousel\Api\Data\CarouselInterface;
use Magento\Framework\Data\OptionSourceInterface;

class CarouselOptions implements OptionSourceInterface
{
    /**
     * @var CarouselRepositoryInterface
     */
    private $carouselRepository;

    /**
     * CarouselOptions constructor.
     * @param CarouselRepositoryInterface $carouselRepository
     */
    public function __construct(
        CarouselRepositoryInterface $carouselRepository
    )
    {
        $this->carouselRepository = $carouselRepository;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $collection = $this->getCollection();
        $options = [];
        if ($collection->getSize()) {
            /** @var CarouselInterface $item */
            foreach ($collection as $item) {
                $options[] = [
                    'value' => $item->getId(),
                    'label' => $item->getTitle()
                ];
            }
        }
        return $options;
    }

    /**
     * @return \Codilar\Carousel\Model\ResourceModel\Carousel\Collection
     */
    protected function getCollection()
    {
        return $this->carouselRepository->getCollection()->addFieldToFilter("is_active", 1);
    }
}