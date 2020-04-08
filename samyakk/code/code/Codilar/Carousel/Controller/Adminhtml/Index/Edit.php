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
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Backend\Model\View\Result\Page;

class Edit extends Action
{
    /**
     * @var CarouselRepositoryInterface
     */
    private $carouselRepository;

    /**
     * Edit constructor.
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
            if ($id) {
                $carousel = $this->carouselRepository->load($id);
            }
            /** @var Page $result */
            $result = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            $result->setActiveMenu("Codilar_Carousel::carousel")
                ->addBreadcrumb(
                    __("Carousel"),
                    __("Carousel")
                )
                ->addBreadcrumb(
                    __("Add"),
                    __("Add"),
                    $this->getUrl('*/*')
                )
                ->addBreadcrumb(
                    $id ? __("Edit Carousel") : __("Add Carousel"),
                    $id ? __("Edit Carousel") : __("Add Carousel")
                );
            $result->getConfig()->getTitle()->prepend(__('Carousel'));
            $result->getConfig()->getTitle()->prepend($id ? $carousel->getTitle() : __('New Carousel'));
            return $result;
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__("This Carousel no longer exists"));
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath("*/*");
        }
    }
}