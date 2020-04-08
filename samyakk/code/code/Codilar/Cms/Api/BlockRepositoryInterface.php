<?php
/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */


namespace Codilar\Cms\Api;


interface BlockRepositoryInterface
{
    /**
     * @return \Codilar\Cms\Api\Data\BlockInterface[]
     */
    public function getBlocks();

    /**
     * @return \Magento\Cms\Api\Data\BlockInterface
     */
    public function getFooterBlock();

    /**
     * @param bool $isHomepage
     * @return \Magento\Cms\Model\ResourceModel\Block\Collection
     */
    public function getCollection($isHomepage = true);
}
