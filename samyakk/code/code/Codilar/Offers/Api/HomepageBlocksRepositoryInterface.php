<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Offers\Api;

use Codilar\Offers\Api\Data\HomepageBlocksInterface;
use Codilar\Offers\Model\ResourceModel\HomepageBlocks\Collection;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

interface HomepageBlocksRepositoryInterface
{
    /**
     * @param HomepageBlocksInterface $page
     * @return HomepageBlocksInterface
     */
    public function save(HomepageBlocksInterface $page);

    /**
     * @param int $id
     * @return HomepageBlocksInterface
     */
    public function getById($id);

    /**
     * @param SearchCriteriaInterface $criteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria);

    /**
     * @param HomepageBlocksInterface $page
     * @return boolean
     */
    public function delete(HomepageBlocksInterface $page);

    /**
     * @param int $id
     * @return boolean
     */
    public function deleteById($id);

    /**
     * @return Collection
     */
    public function getCollection();

    /**
     * @return \Codilar\Offers\Api\Data\HomepageBlocksInterface[]
     */
    public function getBlocks();
}
