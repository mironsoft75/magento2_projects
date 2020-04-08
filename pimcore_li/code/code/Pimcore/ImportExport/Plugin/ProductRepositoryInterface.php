<?php

namespace Pimcore\ImportExport\Plugin;


use Magento\Catalog\Model\Product\Gallery\Processor as GalleryProcessor;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Model\ResourceModel\Product\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Io\File;
use Magento\ProductVideo\Model\Product\Attribute\Media\ExternalVideoEntryConverter;
use Pimcore\Aces\Api\AcesProductsManagementInterface;
use Pimcore\Aces\Api\AcesProductsRepositoryInterface;
use Pimcore\Aces\Api\Data\AcesProductsInterface;
use Pimcore\ImportExport\Helper\AttributeOptionsHelper;
use Pimcore\ImportExport\Model\MediaProcessor;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Model\Product\Attribute\Backend\Media\EntryConverterPool;
use \Magento\Catalog\Model\Product\Media\Config as MediaConfig;

class ProductRepositoryInterface
{
    CONST IMAGES_FROM_API = 'pimcore_api/';
    CONST BED_LENGTH = 'bed_length';
    private $ymmMultiSelectAttributes = [
        'base_vehicle_id' => [
            'label' => 'Base vehicle ID',
        ],
        'year_id' => [
            'label' => 'Year',
        ],
        'make_name' => [
            'label' => 'Make',
        ],
        'model_name' => [
            'label' => 'Model',
        ],
        'sub_model' => [
            'label' => 'Sub Model Name',
        ],
        'sub_detail' => [
            'label' => 'Sub Detail',
        ],
        'body_type' => [
            'label' => 'Body Type',
        ],
        'bed_type' => [
            'label' => 'Bed Type',
        ],
        'cab_type' => [
            'label' => 'Cab Type',
        ],
        'bed_length' => [
            'label' => 'Bed Length',
        ],
        'no_of_doors' => [
            'label' => 'No of Doors',
        ],
        'fitment_position' => [
            'label' => 'Position',
        ]
    ];

    const WEBSITE_IDS = 'website_ids';
    const OPTION_ATTRIBUTES = ['brand_name', 'style', 'material', 'popularity_code'];
    protected $optionAttributes = [];
    protected $productExtensionFactory;
    protected $productFactory;
    /**
     * @var AttributeOptionsHelper
     */
    private $attributeOptionsHelper;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var Action
     */
    private $productAction;
    /**
     * @var AcesProductsInterface
     */
    private $acesProductsInterface;
    /**
     * @var AcesProductsRepositoryInterface
     */
    private $acesProductsRepository;
    /**
     * @var AcesProductsManagementInterface
     */
    private $acesProductsManagement;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var EntryConverterPool
     */
    private $mediaGalleryEntryConverterPool;
    /**
     * @var Filesystem
     */
    private $fileSystem;
    /**
     * @var Filesystem\DirectoryList
     */
    private $directoryList;
    /**
     * @var Processor
     */
    private $processor;
    /**
     * @var GalleryProcessor
     */
    private $galleryProcessor;
    /**
     * @var File
     */
    private $file;
    /**
     * @var MediaConfig
     */
    private $mediaConfig;
    /**
     * @var MediaProcessor
     */
    private $mediaProcessor;

    /**
     * ProductRepositoryInterface constructor.
     * @param \Magento\Catalog\Api\Data\ProductExtensionFactory $productExtensionFactory
     * @param \Magento\Catalog\Model\ProductFactory             $productFactory
     * @param AttributeOptionsHelper                            $attributeOptionsHelper
     * @param ProductRepository                                 $productRepository
     * @param Action                                            $productAction
     * @param AcesProductsRepositoryInterface                   $acesProductsRepository
     * @param AcesProductsManagementInterface                   $acesProductsManagement
     * @param LoggerInterface                                   $logger
     * @param EntryConverterPool                                $mediaGalleryEntryConverterPool
     * @param Filesystem                                        $fileSystem
     * @param Filesystem\DirectoryList                          $directoryList
     * @param GalleryProcessor                                  $galleryProcessor
     * @param File                                              $file
     * @param MediaConfig                                       $mediaConfig
     * @param MediaProcessor                                    $mediaProcessor
     */
    public function __construct(
        \Magento\Catalog\Api\Data\ProductExtensionFactory $productExtensionFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        AttributeOptionsHelper $attributeOptionsHelper,
        ProductRepository $productRepository,
        Action $productAction,
        AcesProductsRepositoryInterface $acesProductsRepository,
        AcesProductsManagementInterface $acesProductsManagement,
        LoggerInterface $logger,
        EntryConverterPool $mediaGalleryEntryConverterPool,
        Filesystem $fileSystem,
        Filesystem\DirectoryList $directoryList,
        GalleryProcessor $galleryProcessor,
        File $file,
        MediaConfig $mediaConfig,
        MediaProcessor $mediaProcessor
    )
    {
        $this->productFactory = $productFactory;
        $this->productExtensionFactory = $productExtensionFactory;
        $this->attributeOptionsHelper = $attributeOptionsHelper;
        $this->productRepository = $productRepository;
        $this->productAction = $productAction;
        $this->acesProductsRepository = $acesProductsRepository;
        $this->acesProductsManagement = $acesProductsManagement;
        $this->logger = $logger;
        $this->mediaGalleryEntryConverterPool = $mediaGalleryEntryConverterPool;
        $this->fileSystem = $fileSystem;
        $this->directoryList = $directoryList;
        $this->galleryProcessor = $galleryProcessor;
        $this->file = $file;
        $this->mediaConfig = $mediaConfig;
        $this->mediaProcessor = $mediaProcessor;
    }

    /**
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $subject
     * @param \Closure                                        $proceed
     * @param \Magento\Catalog\Api\Data\ProductInterface      $product
     * @param bool                                            $saveOptions
     * @return mixed
     */
    public function aroundSave(\Magento\Catalog\Api\ProductRepositoryInterface $subject,
                               \Closure $proceed,
                               $product, $saveOptions = false)
    {
        try {
            $fitmentData = $product->getExtensionAttributes()->getFitmentData();
            $digitalAssets = $product->getExtensionAttributes()->getDigitalAssets();
            foreach (self::OPTION_ATTRIBUTES as $optionAttribute) {
                $data = $product->getData($optionAttribute);
                if (isset($data)) {
                    $this->optionAttributes[$optionAttribute] = $data;
                }
            }
            $product = $proceed($product, $saveOptions);
            if ($product->getId()) {

                $this->updateProductSalesMsrp($product);
                $this->processFitmentData($product, $fitmentData);
                $this->updateMultiSelectAttrOptions($product, self::OPTION_ATTRIBUTES);
                if (count($digitalAssets)) {
                    $this->processDigitalAssets($product->getId(), $digitalAssets);
                }

            }
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $this->logger->info($e->getMessage());
        }
        return $product;
    }

    /**
     * @param      $attributeCode
     * @param      $data
     * @param bool $exploded
     * @return array|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getAttributeOptions($attributeCode, $data, $exploded = false)
    {
        if (empty($data)) {
            return $data;
        }
        $data = $exploded ? $data : explode(",", $data);
        $result = [];
        $helper = $this->attributeOptionsHelper;
        foreach ($data as $value) {
            //$result[] = $helper->createOrGetId($attributeCode, $value);
            $result[] = $helper->processProductOptionId($attributeCode, $value);
        }
        $result = implode(",", $result);
        return $result;
    }

    /**
     * @param $productId
     * @param $storeId
     * @param $attributeCode
     * @param $attributeValue
     */
    private function updateAttributeValue($productId, $storeId, $attributeCode, $attributeValue)
    {
        $this->productAction->updateAttributes(
            [$productId],
            [$attributeCode => $attributeValue],
            $storeId
        );
    }

    /**
     * @param $product
     * @param $fitmentData
     */
    private function processFitmentData($product, $fitmentData)
    {
        $data = [];
        if (count($fitmentData)) {
            $this->acesProductsManagement->clearProductFitmentData($product);
            foreach ($fitmentData as $fitment) {
                $dataforTable = [];
                foreach ($this->ymmMultiSelectAttributes as $attributeCode => $value) {
                    $fitmentAttributeValue = $this->getFitmentAttributeValue($attributeCode, $fitment);
                    $data[$attributeCode][] = $fitmentAttributeValue;
                    $dataforTable[$attributeCode] = $fitmentAttributeValue;
                }
                $this->acesProductsManagement->addProductFitment($product, $dataforTable);
            }
            $this->updateFitmentAttributes($data, $product);
        }
    }

    /**
     * @param $product
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function updateMultiSelectAttrOptions($product)
    {
        if (count($this->optionAttributes)) {
            foreach ($this->optionAttributes as $attributeCode => $data) {
                $optionIds = $this->getAttributeOptions($attributeCode, $data);
                $this->updateAttributeValue($product->getId(), 0, $attributeCode, $optionIds);
            }
        }

    }

    /**
     * @param $data
     * @param $product
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function updateFitmentAttributes($data, $product)
    {
        foreach ($data as $attributeCode => $value) {
            $value = array_filter($value);
            if (count($value) && !empty($value)) {
                $optionIds = $this->getAttributeOptions($attributeCode, $value, true);
                $this->updateAttributeValue($product->getId(), 0, $attributeCode, $optionIds);
            }
        }
    }

    private function processDigitalAssets($productId, $digitalAssets)
    {
        $mediaAttributeCodes = $this->mediaConfig->getMediaAttributeCodes();
        if (count($digitalAssets)) {
            /*Remove Existing media gallery images in order to update new digital assets*/
            $product = $this->productRepository->getById($productId);
            //$this->removeExistingMediaGallery($product);
            if ($product->getId()) {
                foreach ($digitalAssets as $data) {
                    if ($data->getMediaType() == 'external-video') {
                        $this->uploadVideo($data, $product);
                    } else {
                        $product->setStoreId(0);
                        $placeholders = $data->getPlaceholders();
                        $validPlaceholders = null;
                        if (count($placeholders)) {
                            foreach ($placeholders as $atttribute) {
                                if (in_array($atttribute, $mediaAttributeCodes)) {
                                    $validPlaceholders[] = $atttribute;
                                }
                            }
                        }
                        $this->galleryProcessor->addImage($product, $this->getImagePath($data->getUrl()), $validPlaceholders, false, false);
                    }
                    $product->save();
                }
            }
        }
    }

    //@codeCoverageIgnoreEnd


    /**
     * @param array $mediaGallery
     * @return \Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterface[]
     */
    private function convertToMediaGalleryInterface(array $mediaGallery, $productShell)
    {
        $entries = [];
        foreach ($mediaGallery as $image) {
            $entry = $this
                ->mediaGalleryEntryConverterPool
                ->getConverterByMediaType($image['media_type'])
                ->convertTo($productShell, $image);
            $entries[] = $entry;
        }
        return $entries;
    }


    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     */
    private function removeExistingMediaGallery(&$product)
    {
        $existingMediaGalleryEntries = $product->getMediaGalleryEntries();
        foreach ($existingMediaGalleryEntries as $key => $entry) {
            unset($existingMediaGalleryEntries[$key]);
        }
    }

    private function getImagePath($url)
    {
        $basename = pathinfo($url, PATHINFO_BASENAME);
        $mediaPath = $this->directoryList->getPath('media') . '/' . self::IMAGES_FROM_API;
        if (!file_exists($mediaPath)) {
            $this->file->mkdir($mediaPath, 0777);
        }
        $filePath = $mediaPath . $basename;
        if (file_exists($filePath)) {
            $this->logger->info($filePath . " Already existed,so skipping to get from " . $url);
            return $filePath;
        }
        $url = str_replace(' ', '%20', $url);
        $this->file->write($filePath, file_get_contents($url), 0777);
        return $filePath;
    }

    /**
     * @param $data
     * @param $product
     * @throws LocalizedException
     */
    private function uploadVideo($data, &$product)
    {
        if (!array_key_exists(0, $data->getPlaceholders())) {
            throw new LocalizedException(__("Please enter the image placeholder for product video"));
        }
        $path = $this->getImagePath($data->getPlaceholders()[0]);
        $videoData = [
            'video_title' => $data->getLabel(),
            'video_description' => "",
            'thumbnail' => $path,
            'video_provider' => "youtube",
            'video_metadata' => null,
            'disabled' => false,
            'video_url' => $data->getUrl(), //set your youtube video url
            'media_type' => ExternalVideoEntryConverter::MEDIA_TYPE_CODE,
        ];

        //download thumbnail image and save locally under pub/media
        $videoData['file'] = $path;
        if ($product->hasGalleryAttribute()) {
            $this->mediaProcessor->addVideo(
                $product,
                $videoData,
                ['image', 'small_image', 'thumbnail'],
                false,
                true
            );
        }
    }

    private function getFitmentAttributeValue($attributeCode, $fitment)
    {
        if ($attributeCode == self::BED_LENGTH && !empty($fitment->getData($attributeCode))) {
            $bedLengthInFootInches = round($fitment->getData($attributeCode) * 0.0833333, 1);
            $bedLengthInFootInches = explode('.', $bedLengthInFootInches);
            $bedLengthInFoot = isset($bedLengthInFootInches[0]) ? $bedLengthInFootInches[0] . "'" : '';
            $bedLengthInInches = isset($bedLengthInFootInches[1]) ? $bedLengthInFootInches[1] . '"' : '';
            return $bedLengthInFoot . $bedLengthInInches;
        }
        return $fitment->getData($attributeCode);
    }

    private function updateProductSalesMsrp($product)
    {
        $mapPrice = $product->getData('price_map');
        $price = $product->getData('price');
        if ($price && $mapPrice) {
            $msrpSalesSort = ($price / $mapPrice) - 1;
        }
        $this->updateAttributeValue($product->getId(), 0, 'msrp_sales_sort', $msrpSalesSort);
    }
}

