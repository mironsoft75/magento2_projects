<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Controller\Adminhtml\Index;

use Codilar\Carousel\Api\CarouselRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;

class Delete extends Action
{
    /**
     * @var CarouselRepositoryInterface
     */
    private $carouselRepository;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param CarouselRepositoryInterface $carouselRepository
     */
    public function __construct(
        Action\Context $context,
        CarouselRepositoryInterface $carouselRepository
    )
    {
        parent::__construct($context);
        $this->carouselRepository = $carouselRepository;
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
            $form = $this->carouselRepository->load($id);
            $this->carouselRepository->delete($form);
            $this->messageManager->addSuccessMessage(__("Carousel deleted successfully"));
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__("The carousel no longer exists"));
        } catch (CouldNotDeleteException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
        return $this->resultRedirectFactory->create()->setPath('*/*');
    }
}