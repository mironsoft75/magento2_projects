<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Cms\Model;

use Codilar\Api\Api\AbstractApi;
use Codilar\Api\Helper\Cookie;
use Codilar\Api\Helper\Emulator;
use Codilar\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Cms\Model\ResourceModel\Block\CollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Webapi\Rest\Response;

class BlockRepository extends AbstractApi implements BlockRepositoryInterface
{

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var Emulator
     */
    private $emulator;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var \Magento\Cms\Api\BlockRepositoryInterface
     */
    private $blockRepository;

    /**
     * BlockRepository constructor.
     * @param Cookie $cookieHelper
     * @param RequestInterface $request
     * @param Response $response
     * @param Session $customerSession
     * @param DataObjectFactory $dataObjectFactory
     * @param CollectionFactory $collectionFactory
     * @param Emulator $emulator
     * @param Config $config
     * @param \Magento\Cms\Api\BlockRepositoryInterface $blockRepository
     */
    public function __construct(
        Cookie $cookieHelper,
        RequestInterface $request,
        Response $response,
        Session $customerSession,
        DataObjectFactory $dataObjectFactory,
        CollectionFactory $collectionFactory,
        Emulator $emulator,
        Config $config,
        \Magento\Cms\Api\BlockRepositoryInterface $blockRepository
    )
    {
        parent::__construct($cookieHelper, $request, $response, $customerSession, $dataObjectFactory);
        $this->collectionFactory = $collectionFactory;
        $this->emulator = $emulator;
        $this->config = $config;
        $this->blockRepository = $blockRepository;
    }

    /**
     * @return \Codilar\Cms\Api\Data\BlockInterface[]
     */
    public function getBlocks()
    {
        $collection = $this->getCollection();
        $blocks = [];
        if ($collection) {
            /** @var \Codilar\Cms\Api\Data\BlockInterface $item */
            foreach ($collection as $item) {
                $blocks[] = [
                    "id" => $item->getId(),
                    "title" => $item->getTitle(),
                    "sort_order" => $item->getSortOrder(),
                    "content" => $item->getContent(),
                    "design_identifier" => $item->getDesignIdentifier()
                ];
            }
        }
        return $this->sendResponse($blocks);
    }

    /**
     * @return \Magento\Cms\Api\Data\BlockInterface
     */
    public function getFooterBlock()
    {
        $blockId = $this->config->getFooterBlockIdentifier();
        try {
            $block = $this->blockRepository->getById($blockId);
        } catch (LocalizedException $e) {
            $block = $this->getNewDataObject([
                "status" => false,
                "message" => $e->getMessage()
            ]);
        }
        return $this->sendResponse($block);
    }

    /**
     * @param bool $isHomepage
     * @param null|int $storeId
     * @return \Magento\Cms\Model\ResourceModel\Block\Collection|bool
     */
    public function getCollection($isHomepage = true, $storeId = null)
    {
        /** @var \Magento\Cms\Model\ResourceModel\Block\Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter(BlockInterface::IS_ACTIVE, 1);

        if ($isHomepage) {
            $collection->addFieldToFilter(\Codilar\Cms\Api\Data\BlockInterface::SHOW_IN_HOMEPAGE, 1);
        }
        if ($storeId) {
            $collection->addStoreFilter($storeId);
        }
        $collection->setOrder(\Codilar\Cms\Api\Data\BlockInterface::SORT_ORDER, SortOrder::SORT_ASC);
        if ($collection->getSize()) {
            return $collection;
        }
        return false;
    }
}
