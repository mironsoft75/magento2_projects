<?php
/**
 * Created by PhpStorm.
 * User: codilar
 * Date: 19/6/19
 * Time: 11:03 AM
 */

namespace Codilar\ProductImageAndVideos\Helper;

use Magento\Catalog\Model\Product\Attribute\Repository as ProductAttribute;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Thai\S3\Helper\Data as DataHelper;

/**
 * Class Data
 * @package Codilar\ProductImageAndVideos\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $client;
    /**
     * @var DataHelper
     */
    private $helper;
    /**
     * @var Context
     */
    private $context;
    /**
     * @var DirectoryList
     */
    public $directoryList;
    const DELETE_PRODUCT = "delete_product";
    const YES = "Yes";
    const NO = "No";
    const TIME_TO_GENERATE_THUMBNAIL = 'product_video_and_image_options/general/time_to_generate_thumbnail';
    const GENERATED_FOLDER_NAME = 'product_video_and_image_options/general/generated_folder_name';
    const ALLOWED_URLS_BEFORE_LOGIN = 'url_restrictions/general/before_login';
    const ALLOWED_URLS_AFTER_LOGIN = 'url_restrictions/general/after_login';
    const URL_RESTRICTION_ENABLED = 'url_restrictions/general/enable';
    const SELECT_ATTRIBUTE = 'product_video_and_image_options/general/select_attribute';

    /**
     * @var ProductAttribute $productAttribute
     */
    private $productAttribute;
    /**
     * @var string $deleteProductYes
     */
    private $deleteProductYes;
    /**
     * @var string $deleteProductNo
     */
    private $deleteProductNo;
    private $productRepository;
    private $storeManager;

    /**
     * Data constructor.
     * @param DataHelper $helper
     * @param Context $context
     * @param DirectoryList $directoryList
     * @param ProductAttribute $productAttribute
     * @param ProductRepository $productRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        DataHelper $helper,
        Context $context,
        DirectoryList $directoryList,
        ProductAttribute $productAttribute,
        ProductRepository $productRepository,
        StoreManagerInterface $storeManager
    )
    {
        parent::__construct($context);
        $this->helper = $helper;
        $this->directoryList = $directoryList;
        $this->productAttribute = $productAttribute;
        $this->context = $context;
        $this->productRepository = $productRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getTimeToGenerateThumbnail($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::TIME_TO_GENERATE_THUMBNAIL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getGeneratedFolderName($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::GENERATED_FOLDER_NAME,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * SET S3 CREDENTIAL
     * @return \Aws\S3\S3Client
     */
    public function setS3Credentials()
    {
        $options = [
            'version' => 'latest',
            'region' => $this->helper->getRegion(),
            'credentials' => [
                'key' => $this->helper->getAccessKey(),
                'secret' => $this->helper->getSecretKey(),
            ],
        ];
        $this->client = new \Aws\S3\S3Client($options);
        if (!$this->client->doesBucketExist($this->helper->getBucket())) {
            echo('The AWS credentials you provided did not work. Please review your details and try again. You can do so using our config script.');
        } else {
            return $this->client;
        }
    }

    /**
     * @param $object
     * @return bool
     */
    public function isVideo($object)
    {

        return explode(".", $object["Key"])[count(explode(".", $object["Key"])) - 1] == "mp4";
    }

    /**
     * Check the file type (video or image)
     * @param $object
     * @param $client
     * @param $bucketName
     * @return bool|string
     */
    public function checkFileType($object, $client, $bucketName)
    {
        $sourceFileName = $client->getObjectUrl($bucketName, $object["Key"]);
        $file = get_headers($sourceFileName, 1);
        if (strstr($file['Content-Type'], "image/")) {
            return "image";
        }
        if (strstr($file['Content-Type'], "video/")) {
            return "video";
        } else {
            return false;
        }
    }

    /**
     * Media directory name for the temporary file storage
     * pub/media/tmp
     *
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getMediaDirTmpDir()
    {
        return $this->directoryList->getPath(DirectoryList::MEDIA) . DIRECTORY_SEPARATOR . 'tmp';
    }

    /**
     * @param $product
     * @param $object
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isVariantTypeExist($product, $object)
    {
        $product = $this->productRepository->get($product->getSku());
        return $product->getVariantType() == explode("/", $object["Key"])[0];
    }

    /**
     * @param $product
     * @param $object
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isStockCodeExist($product, $object)
    {
        try {
            $product = $this->productRepository->get($product->getSku());
        } catch (\Exception $e) {
            throw new \Exception("Error in Loading Product" . __CLASS__);
        }
        return $product->getStockCode() == explode("/", $object["Key"])[0];
    }

    /**
     * @param $object
     * @return array
     */
    public function getObjectDirectory($object)
    {
        $objectDir = explode("/", $object["Key"]);
        return $objectDir;
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getDeleteProductValuesYes()
    {
        $options = $this->productAttribute->get(self::DELETE_PRODUCT)->getOptions();
        foreach ($options as $option) {
            if ($option->getLabel() == self::YES) {
                $this->deleteProductYes = $option->getValue();
            }
        }
        return $this->deleteProductYes;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getDeleteProductValuesNO()
    {
        $options = $this->productAttribute->get(self::DELETE_PRODUCT)->getOptions();
        foreach ($options as $option) {
            if ($option->getLabel() == self::NO) {
                $this->deleteProductNo = $option->getValue();
            }
        }
        return $this->deleteProductNo;
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getAllowedUrlsBeforeLogin($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::ALLOWED_URLS_BEFORE_LOGIN,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getAllowedUrlsAfterLogin($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::ALLOWED_URLS_AFTER_LOGIN,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getUrlRestrictionEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::URL_RESTRICTION_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Fetch Attribute Type to generate Video and Images
     * @param null $storeId
     * @return mixed
     */
    public function getSelectAttribute($storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::SELECT_ATTRIBUTE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @return array
     */
    public function getAllStores()
    {
        $storeIds = [];
        $stores = $this->storeManager->getStores();
        foreach ($stores as $store) {
            $storeIds[] = $store->getId();
        }
        array_push($storeIds, "0");
        return $storeIds;
    }

    /**
     * @param $sku
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function setThumbnailImage($sku)
    {
        $storeIds = $this->getAllStores();
        foreach ($storeIds as $storeId) {
            $product = $this->productRepository->get($sku);
            $product->setStoreId($storeId);
            $this->storeManager->setCurrentStore($storeId);
            $mediaGalleryEntriesTemp = [];
            $i = 1;
            foreach ($product->getMediaGalleryEntries() as $item) {
                if ($i === 1) {
                    $item->setTypes(['image', 'small_image', 'thumbnail']);
                }
                $mediaGalleryEntriesTemp[] = $item;
                $i++;
            }
            $product->setMediaGalleryEntries($mediaGalleryEntriesTemp);
            $this->productRepository->save($product);
        }
    }
}
