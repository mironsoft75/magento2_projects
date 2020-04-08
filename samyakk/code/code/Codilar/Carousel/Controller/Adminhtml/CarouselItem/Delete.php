<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Controller\Adminhtml\CarouselItem;

use Codilar\Carousel\Api\CarouselItemRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;

class Delete extends Action
{
    /**
     * @var CarouselItemRepositoryInterface
     */
    private $carouselItemRepository;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param CarouselItemRepositoryInterface $carouselItemRepository
     */
    public function __construct(
        Action\Context $context,
        CarouselItemRepositoryInterface $carouselItemRepository
    )
    {
        parent::__construct($context);
        $this->carouselItemRepository = $carouselItemRepository;
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
        $id = $this->getRequest()->getParam('id');
        try {
            $form = $this->carouselItemRepository->load($id);
            $this->carouselItemRepository->delete($form);
            $this->messageManager->addSuccessMessage(__("Carousel item deleted successfully"));
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__("The carousel item no longer exists"));
        } catch (CouldNotDeleteException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $this->resultRedirectFactory->create()->setPath('*/*');
    }
}