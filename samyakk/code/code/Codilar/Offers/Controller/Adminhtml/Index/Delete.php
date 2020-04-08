<?php
/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Offers\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;

class Delete extends Action
{

    /**
     * @var \Codilar\Offers\Api\HomepageBlocksRepositoryInterface
     */
    private $homepageBlocksRepository;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param ResultFactory $resultFactory
     * @param \Codilar\Offers\Api\HomepageBlocksRepositoryInterface $homepageBlocksRepository
     */
    public function __construct(Action\Context $context,
                                ResultFactory $resultFactory,
                                \Codilar\Offers\Api\HomepageBlocksRepositoryInterface $homepageBlocksRepository)
    {
        parent::__construct($context);
        $this->homepageBlocksRepository = $homepageBlocksRepository;
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
        $id = $this->getRequest()->getParam('id');
        if($id) {
            try{
                $this->homepageBlocksRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('Successfully deleted'));
            }catch (\Exception $exception){
                print_r($exception->getMessage());die;
            }
        }
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}