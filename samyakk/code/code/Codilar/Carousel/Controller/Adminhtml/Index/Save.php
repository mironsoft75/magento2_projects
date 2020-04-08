<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Controller\Adminhtml\Index;


use Codilar\Carousel\Api\Data\CarouselItemsInterface;
use Codilar\Carousel\Api\Data\CarouselInterface;
use Codilar\Carousel\Api\CarouselItemRepositoryInterface;
use Codilar\Carousel\Api\CarouselRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends Action
{
    /**
     * @var CarouselRepositoryInterface
     */
    private $carouselRepository;
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    /**
     * @var CarouselItemRepositoryInterface
     */
    private $carouselItemRepository;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param CarouselRepositoryInterface $carouselRepository
     * @param DataPersistorInterface $dataPersistor
     * @param CarouselItemRepositoryInterface $carouselItemRepository
     */
    public function __construct(
        Action\Context $context,
        CarouselRepositoryInterface $carouselRepository,
        DataPersistorInterface $dataPersistor,
        CarouselItemRepositoryInterface $carouselItemRepository
    )
    {
        parent::__construct($context);
        $this->carouselRepository = $carouselRepository;
        $this->dataPersistor = $dataPersistor;
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
        $data = $this->getRequest()->getParams();
        try {
            if (!array_key_exists('id', $data)) {
                throw new NoSuchEntityException(__("The carousel no longer exists"));
            }
            if ($data['id']) {
                try {
                    $carousel = $this->carouselRepository->load($data['id']);
                } catch (NoSuchEntityException $e) {
                    throw new LocalizedException(__("The carousel no longer exists"));
                }
            } else {
                $carousel = $this->carouselRepository->create();
            }
            $carousel->setTitle($data['title'])
                ->setIsActive($data['is_active'])
                ->setSortOrder($data['sort_order'])
                ->setDesignIdentifier($data['design_identifier'])
                ->setStoreViews(implode(',', $data['store_views']));
            try {
                $carousel = $this->carouselRepository->save($carousel);
            } catch (CouldNotSaveException $couldNotSaveException) {
                $this->messageManager->addErrorMessage($couldNotSaveException->getMessage());
                $backUrl = $this->getUrl("*/*/edit", ['id' => $carousel->getId()]);
                throw new \Exception("Go back");
            }
            $this->messageManager->addSuccessMessage(__("Carousel saved successfully"));
            if (array_key_exists('back', $data)) {
                if ($data['back'] === "continue") {
                    $backUrl = $this->getUrl("*/*/edit", ['id' => $carousel->getId()]);
                } else if ($data['back'] === "duplicate") {
                    $newCarousel = $this->duplicateForm($carousel);
                    $this->messageManager->addSuccessMessage(__("Carousel duplicated successfully"));
                    $backUrl = $this->getUrl("*/*/edit", ['id' => $newCarousel->getId()]);
                }
            } else {
                $backUrl = $this->getUrl('*/*');
            }
        } catch (LocalizedException $localizedException) {
            $this->messageManager->addErrorMessage($localizedException->getMessage());
            $backUrl = $this->getUrl("*/*");
        } catch (\Exception $exception) {
        }
        return $this->resultRedirectFactory->create()->setUrl($backUrl);
    }

    /**
     * @param CarouselInterface $carousel
     * @return CarouselInterface
     */
    protected function duplicateForm(CarouselInterface $carousel) {
        $newCarousel = $this->carouselRepository->create();
        $newCarousel->setTitle($carousel->getTitle())
            ->setSortOrder($carousel->getSortOrder())
            ->setIsActive($carousel->getIsActive())
            ->setStoreViews($carousel->getStoreViews());
        return $this->carouselRepository->save($newCarousel);
    }
}