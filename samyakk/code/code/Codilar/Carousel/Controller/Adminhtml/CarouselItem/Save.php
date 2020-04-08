<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Controller\Adminhtml\CarouselItem;

use Codilar\Carousel\Api\Data\CarouselItemsInterface;
use Codilar\Carousel\Api\CarouselItemRepositoryInterface;
use Codilar\CheckoutPaypal\Helper\RequestData;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Save extends Action
{

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    /**
     * @var CarouselItemRepositoryInterface
     */
    private $carouselItemRepository;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param CarouselItemRepositoryInterface $carouselItemRepository
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        CarouselItemRepositoryInterface $carouselItemRepository,
        ProductRepositoryInterface $productRepository
    )
    {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->carouselItemRepository = $carouselItemRepository;
        $this->productRepository = $productRepository;
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
                throw new NoSuchEntityException(__("The carousel item no longer exists"));
            }
            if ($data['id']) {
                try {
                    $carouselItem = $this->carouselItemRepository->load($data['id']);
                } catch (NoSuchEntityException $e) {
                    throw new LocalizedException(__("The carousel item no longer exists"));
                }
            } else {
                $carouselItem = $this->carouselItemRepository->create();
            }
            if ($data['link_type']) {
                $identifierArray = $this->getIdentifierArray();
                if ($data['link_type'] != "none") {
                    if ($data['link_type'] == 'product') {
                        $sku = $data[$identifierArray[$data['link_type']]];
                        try {
                            $this->productRepository->get($sku);
                            $data['identifier'] = $data[$identifierArray[$data['link_type']]];
                        } catch (NoSuchEntityException $e) {
                            $this->messageManager->addErrorMessage("Product SKU dosent exist");
                            if (!$carouselItem->getId()) {
                                $this->dataPersistor->set('carousel_item', $data);
                            }
                            $backUrl = $this->getUrl("*/*/edit", ['id' => $carouselItem->getId()]);
                            return $this->resultRedirectFactory->create()->setUrl($backUrl);
                        }
                    } else {
                        $data['identifier'] = $data[$identifierArray[$data['link_type']]];
                    }
                } else {
                    $data['identifier'] = $identifierArray[$data['link_type']];
                }
            }
            $carouselItem->setContent($data['content'])
                ->setLink(json_encode(["type" => $data['link_type'], "identifier" => $data['identifier']]))
                ->setCarouselId($data['carousel_id'])
                ->setLabel($data['label']);
            try {
                $carouselItem = $this->carouselItemRepository->save($carouselItem);
                $this->dataPersistor->clear('carousel_item');
            } catch (CouldNotSaveException $couldNotSaveException) {
                $this->messageManager->addErrorMessage($couldNotSaveException->getMessage());
                $backUrl = $this->getUrl("*/*/edit", ['id' => $carouselItem->getId()]);
                throw new \Exception("Go back");
            }
            $this->messageManager->addSuccessMessage(__("Carousel item saved successfully"));
            if (array_key_exists("back", $data)) {
                if ($data['back'] === "continue") {
                    $backUrl = $this->getUrl("*/*/edit", ['id' => $carouselItem->getId()]);
                } else if ($data['back'] === "duplicate") {
                    $newCarousel = $this->duplicateForm($carouselItem);
                    $this->messageManager->addSuccessMessage(__("Carousel item duplicated successfully"));
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
     * @param CarouselItemsInterface $carouselItem
     * @return CarouselItemsInterface
     */
    protected function duplicateForm(CarouselItemsInterface $carouselItem) {
        $newCarouselItem = $this->carouselItemRepository->create();
        $newCarouselItem->setContent($carouselItem->getContent())
            ->setLink($carouselItem->getLink())
            ->setContent($carouselItem->getContent())
            ->setLabel($carouselItem->getLabel())
            ->setCarouselId($carouselItem->getCarouselId());
        return $this->carouselItemRepository->save($newCarouselItem);
    }

    /**
     * @return array
     */
    protected function getIdentifierArray()
    {
        return [
            "product" => "product_data",
            "category" => "category_data",
            "cms" => "cms_data",
            "none" => "#"
        ];
    }
}