<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Cms\Model\Config\Source;

use Codilar\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Framework\Data\OptionSourceInterface;

class Blocks implements OptionSourceInterface
{
    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;

    /**
     * Blocks constructor.
     * @param BlockRepositoryInterface $blockRepository
     */
    public function __construct(
        BlockRepositoryInterface $blockRepository
    )
    {
        $this->blockRepository = $blockRepository;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $data = [];
        $collection = $this->getCollection();
        /** @var BlockInterface $item */
        foreach ($collection as $item) {
            $data[] = [
                "value" => $item->getId(),
                "label" => $item->getTitle()
            ];
        }
        return $data;
    }

    /**
     * @return \Magento\Cms\Model\ResourceModel\Block\Collection
     */
    protected function getCollection()
    {
        return $this->blockRepository->getCollection(false);
    }
}
