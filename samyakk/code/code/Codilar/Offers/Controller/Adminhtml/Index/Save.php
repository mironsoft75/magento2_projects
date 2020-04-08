<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Offers\Controller\Adminhtml\Index;

use Codilar\Core\Helper\Data;
use Codilar\Offers\Api\HomepageBlocksRepositoryInterface;
use Codilar\Offers\Model\HomepageBlocksFactory;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;

class Save extends Action
{
    /**
     * @var HomepageBlocksRepositoryInterface
     */
    private $homepageBlocksRepository;
    /**
     * @var HomepageBlocksFactory
     */
    private $homepageBlocksFactory;
    /**
     * @var Data
     */
    private $coreHelper;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param HomepageBlocksRepositoryInterface $homepageBlocksRepository
     * @param HomepageBlocksFactory $homepageBlocksFactory
     * @param Data $coreHelper
     */
    public function __construct(
        Action\Context $context,
        HomepageBlocksRepositoryInterface $homepageBlocksRepository,
        HomepageBlocksFactory $homepageBlocksFactory,
        Data $coreHelper
    )
    {
        parent::__construct($context);
        $this->homepageBlocksRepository = $homepageBlocksRepository;
        $this->homepageBlocksFactory = $homepageBlocksFactory;
        $this->coreHelper = $coreHelper;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $model = $this->homepageBlocksFactory->create();
        if ($data['id']) {
            $model = $this->homepageBlocksRepository->getById($data['id']);
        } else {
            unset($data['id']);
            $data['created_at'] = $this->coreHelper->getCurrentDate();
        }

        if ($data['product_content']) {
            $data['block_data'] = $data['product_content'];
        }
        $time = $data['start_date'] < $data['end_date'];
        if (!$time) {
            $this->messageManager->addErrorMessage(__("Start date cannot be greater than end date!"));
            return $this->_redirect($this->_redirect->getRefererUrl());
        }
        if ($data['block_data'] && $data['product_content']) {
            $blockData = json_decode($data['block_data'], true);
            $blockData = array_keys($blockData);
            $data['block_data'] = implode(",", $blockData);
        }
        unset($data['product_content']);
        unset($data['key']);
        unset($data['content']);
        unset($data['form_key']);
        $model->addData($data);
        try {
            $save = $this->homepageBlocksRepository->save($model);
            if ($save) {
                $this->messageManager->addSuccessMessage(__("Block is saved"));
                return $this->_redirect($this->getUrl("*/*/index"));
            }
        } catch (\Exception $e) {
            $this->messageManager->addSuccessMessage(__("An error occurred while saving the block!"));
            return $this->_redirect($this->getUrl("*/*/index"));
        }
    }
}