<?php
/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Offers\Controller\Adminhtml\Index;


use Codilar\Offers\Api\HomepageBlocksRepositoryInterface;
use Codilar\Offers\Model\HomepageBlocks;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Codilar\Offers\Model\ResourceModel\HomepageBlocks\CollectionFactory;

class massEnable extends Action
{
    /**
     * @var Filter
     */
    private $filter;
    /**
     * @var HomepageBlocksRepositoryInterface
     */
    private $homepageBlocksRepository;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * massEnable constructor.
     * @param Action\Context $context
     * @param Filter $filter
     * @param HomepageBlocksRepositoryInterface $homepageBlocksRepository
     * @param CollectionFactory $collectionFactory
     * @param ResultFactory $resultFactory
     */
    public function __construct(
        Action\Context $context,
        Filter $filter,
        HomepageBlocksRepositoryInterface $homepageBlocksRepository,
        CollectionFactory $collectionFactory,
        ResultFactory $resultFactory)
    {
        parent::__construct($context);
        $this->filter = $filter;
        $this->homepageBlocksRepository = $homepageBlocksRepository;
        $this->collectionFactory = $collectionFactory;
        $this->resultFactory = $resultFactory;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            foreach ($collection as $item) {
                /** @var HomepageBlocks $item */
                $item->setData('is_active', 1);
                $this->homepageBlocksRepository->save($item);
            }
            $this->messageManager->addSuccessMessage(__("%1 item(s) enabled", $collection->count()));
        } catch (LocalizedException $localizedException) {
            $this->messageManager->addErrorMessage($localizedException->getMessage());
        }
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}