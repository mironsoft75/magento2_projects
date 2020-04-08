<?php

/**
 * Codilar Technologies Pvt. Ltd.
 * @category    pmj-internal
 * @package    pmj-internal
 * @copyright   Copyright (c) 2016 Codilar. (http://www.codilar.com)
 * @purpose     pmj-internal
 * @author       Codilar Team
 **/

namespace Codilar\ProductImageAndVideos\Model\Indexer;

use Codilar\ProductImageAndVideos\Helper\Data;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Model\ResourceModel\Product\Action;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File as DriverFile;
use Magento\Framework\Filesystem\Io\File;
use Magento\MediaStorage\Helper\File\StorageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Thai\S3\Helper\Data as DataHelper;

class Images implements \Magento\Framework\Indexer\ActionInterface, \Magento\Framework\Mview\ActionInterface
{
    /**
     * @var File
     */
    private $file;
    /**
     * @var string $bucketName
     */
    protected $bucketName;
    /**
     * @var string $generatedFolderName
     */
    protected $generatedFolderName;
    const NO_SELECTION = "no_selection";
    const ALL_STORE_VIEW_ID = 0;
    /**
     * @var $client
     */
    private $client;
    /**
     * @var Data $productAndImageDataHelper
     */
    private $productAndImageDataHelper;
    /**
     * @var
     */
    private $context;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var string $deleteProductYes
     */
    private $deleteProductYes;
    /**
     * @var string $deleteProductNo
     */
    private $deleteProductNo;
    private $masterImageRemove=[];
    private $childImageRemove=[];
    private $checkImageExist=[];
    const VARIANT_TYPE = "variant_type";
    const STOCK_CODE = "stock_code";
    const STOCK_CODE_CA= "stock_code_ca";

    /**
     * @var
     */
    private $storage;
    /**
     * @var DriverFile
     */
    private $readFile;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var CollectionFactory
     */
    private $productCollectionFactory;
    /**
     * @var DataHelper
     */
    private $helper;
    private $logger;

    /**
     * Images constructor.
     * @param StoreManagerInterface $storeManager
     * @param Filesystem $filesystem
     * @param DriverFile $readFile
     * @param ProductRepository $productRepository
     * @param CollectionFactory $productCollectionFactory
     * @param StorageFactory $coreFileStorageFactory
     * @param DataHelper $helper
     * @param File $file
     * @param Data $productAndImageDataHelper
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        DriverFile $readFile,
        ProductRepository $productRepository,
        CollectionFactory $productCollectionFactory,
        StorageFactory $coreFileStorageFactory,
        DataHelper $helper,
        File $file,
        Data $productAndImageDataHelper,
        Action $action,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->storeManager = $storeManager;
        $this->filesystem = $filesystem;
        $this->readFile = $readFile;
        $this->productRepository = $productRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->helper = $helper;
        $this->storage = $coreFileStorageFactory->create();
        $this->file = $file;
        $this->productAndImageDataHelper = $productAndImageDataHelper;
        $this->action = $action;
        $this->logger=$logger;
    }

    public function execute($ids)
    {
    }

    /*
     * Will take all of the data and reindex
     * Will run when reindex via command line
     * ADD IMage To Media Gallery
     */
    /**
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function executeFull()
    {
        /** @var array $stockCode */
        $stockCode=[];
        /** @var array $productSku */
        $this->client = $this->productAndImageDataHelper->setS3Credentials();
        $this->bucketName = $this->helper->getBucket();
//        $this->setDeleteProductValues();
//        $iterator = $this->client->getIterator('ListObjects', [
//            'Bucket' => $this->bucketName
//        ]);
        $selectAttribute=$this->productAndImageDataHelper->getSelectAttribute();
//        if ($selectAttribute == self::VARIANT_TYPE) {
//            $productCollection = $this->productCollectionFactory->create();
//            $productCollection->addAttributeToSelect('sku');
//            foreach ($productCollection as $product) {
//                $this->masterImageRemove[$product->getSku()]=true;
//                $this->childImageRemove[$product->getSku()]=true;
//            }
//            $productCollection = $this->productCollectionFactory->create();
//            $productCollection->addAttributeToSelect('sku')
//                ->addFieldToFilter('delete_product', ['eq' => $this->deleteProductNo]);
//            foreach ($iterator as $object) {
//                $objectDir = $this->productAndImageDataHelper->getObjectDirectory($object);
//                foreach ($productCollection as $product) {
//                    if (count($objectDir) > 1) {
//                        if ($this->productAndImageDataHelper->isVariantTypeExist($product, $object)) {
//                            if ($this->productAndImageDataHelper->isVideo($object)) {
//                                // Videos
//                                continue;
//                            } else {
//                                // Image
//                                /**
//                                 * @var \Magento\Catalog\Model\ProductRepository $productDetail
//                                 */
//                                $productDetail = $this->productRepository->get($product->getSku());
//                                $this->isImageExist(
//                                    $productDetail,
//                                    $objectDir,
//                                    $this->client,
//                                    $this->bucketName,
//                                    $object
//                                );
//                            }
//                        }
//                    }
//                }
//            }
//        }
//        elseif ($selectAttribute == self::STOCK_CODE) {
//            $productCollection = $this->productCollectionFactory->create();
//            foreach ($productCollection as $product) {
//                $this->masterImageRemove[$product->getSku()]=true;
//            }
//            foreach ($iterator as $object) {
//                $objectDir = $this->productAndImageDataHelper->getObjectDirectory($object);
//                foreach ($productCollection as $product) {
//                    if (count($objectDir) > 1) {
//                        if ($this->productAndImageDataHelper->isStockCodeExist($product, $object)) {
//                            if ($this->productAndImageDataHelper->isVideo($object)) {
//                                // Videos
//                                continue;
//                            } else {
//                                // Image
//                                $productDetail = $this->productRepository->get($product->getSku());
//                                $this->setProductImageUsingStockCode(
//                                    $productDetail,
//                                    $objectDir,
//                                    $this->client,
//                                    $this->bucketName,
//                                    $object
//                                );
//                            }
//                        }
//                    }
//                }
//            }
//        }
//        if ($selectAttribute == self::STOCK_CODE_CA) {
            /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection */
            $productCollection = $this->productCollectionFactory->create();
            $productCollection->addStoreFilter(self::ALL_STORE_VIEW_ID);
            $productCollection->addAttributeToFilter(
                [
                    ['attribute'=> 'image_custom_urls','null' => true],
                    ['attribute'=> 'image_custom_urls','eq' => ''],
                    ['attribute'=> 'image_custom_urls','eq' => 'NULL']

                ],
                '',
                'left'
            );
            $productCollection->addAttributeToSelect("*");
            foreach ($productCollection as $product) {
                $this->checkImageExist[$product->getSku()] = true;
            }
            foreach ($productCollection as $product) {
                $iterator = $this->client->getIterator('ListObjects', [
                    'Bucket' =>$this->bucketName,
                    "Prefix" =>$product['stock_code'] . "/"
                ]);

                $imageUrls=null;
                foreach ($iterator as $object) {
                    $fileType=$this->productAndImageDataHelper->checkFileType($object, $this->client, $this->bucketName);
                    if ($fileType == "image") {
                        if ($imageUrls == null) {
                            $imageUrls=$this->client->getObjectUrl($this->bucketName, $object["Key"]);
                        } else {
                            $imageUrls=$imageUrls . ',' . $this->client->getObjectUrl($this->bucketName, $object["Key"]);
                        }
                    } else {
                        // Videos | unwanted file
                        continue;
                    }
                }
                if ($imageUrls) {
                    $this->setProductImageUsingStockCodeCac(
                        $product,
                       $imageUrls
                    );
                }
            }
//        }
    }

    /*
     * Works with a set of entity changed (may be massaction)
     */
    public function executeList(array $ids)
    {
    }

    /*
     * Works in runtime for a single entity using plugins
     */
    public function executeRow($id)
    {
    }

    /**
     * @param \Aws\S3\S3Client $client
     * @param $bucket
     * @param $object
     * @param $product
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Exception
     */
    public function setProductImage(\Aws\S3\S3Client $client, $bucket, $object, $product)
    {
        /**
         * @var string $variantType
         */
        $variantType = $product->getVariantType();
        /** @var string $tmpDir */
        $tmpDir = $this->productAndImageDataHelper->getMediaDirTmpDir();
        /** create folder if it is not exists */
        $this->file->checkAndCreateFolder($tmpDir);
        /** @var string $newFileName */
        $newFileName = $tmpDir . "/" . baseName($client->getObjectUrl($bucket, $object["Key"]));
        /** read file from URL and copy it to the new destination */
        $result = $this->file->read($client->getObjectUrl($bucket, $object["Key"]), $newFileName);
        if ($result) {
            /** add saved file to the $product gallery */
            $product->addImageToMediaGallery($newFileName, null, false, false);
            $this->productRepository->save($product);
            if (!$this->isThumbnailExist($product)) {
                $this->productAndImageDataHelper->setThumbnailImage($product->getSku());
            }
            $this->setImageForChildProduct($variantType, $newFileName);
            unlink($newFileName);
        }
    }

    /**
     * @param ProductRepository $productDetail
     * @param array $objectDir
     * @param \Aws\S3\S3Client $client
     * @param $bucket
     * @param $object
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Exception
     */

    public function isImageExist($productDetail, array $objectDir, \Aws\S3\S3Client $client, $bucket, $object)
    {
        if ($this->masterImageRemove[$productDetail->getSku()]==true) {
            $existingMediaGalleryEntries = $productDetail->getMediaGalleryEntries();
            foreach ($existingMediaGalleryEntries as $key => $entry) {
                unset($existingMediaGalleryEntries[$key]);
            }
            $productDetail->setMediaGalleryEntries($existingMediaGalleryEntries);
            $this->productRepository->save($productDetail);
            $this->masterImageRemove[$productDetail->getSku()]=false;
        }

        $this->setProductImage($client, $bucket, $object, $productDetail);
    }

    /**
     * @param $product
     * @return bool
     */
    public function isThumbnailExist($product)
    {
        $thumbnail_url = $product->getData('thumbnail');

        if ($thumbnail_url == self::NO_SELECTION) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param $variantType
     * @param $newFileName
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Exception
     */
    public function setImageForChildProduct($variantType, $newFileName)
    {
        $productCollection = $this->productCollectionFactory->create();
        $childCollection = $productCollection->addAttributeToSelect('sku')
            ->addFieldToFilter('delete_product', ['eq' => $this->deleteProductYes]);
        $childProductCollection = $childCollection->addFieldToFilter('variant_type', $variantType);
        foreach ($childProductCollection as $childProduct) {
            $childProduct = $this->productRepository->get($childProduct->getSku());
            if ($this->childImageRemove[$childProduct->getSku()]==true) {
                $existingMediaGalleryEntries = $childProduct->getMediaGalleryEntries();
                foreach ($existingMediaGalleryEntries as $key => $entry) {
                    unset($existingMediaGalleryEntries[$key]);
                }
                $childProduct->setMediaGalleryEntries($existingMediaGalleryEntries);
                $this->productRepository->save($childProduct);
                $this->childImageRemove[$childProduct->getSku()]=false;
            }
            try {
                $childProduct->addImageToMediaGallery($newFileName, null, false, false);
                $this->productRepository->save($childProduct);
                if (!$this->isThumbnailExist($childProduct)) {
                    $this->productAndImageDataHelper->setThumbnailImage($childProduct->getSku());
                }
            } catch (\Exception $e) {
                throw new \Exception('Error in saving Product while assigning Image for child Product');
            }
        }
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function setDeleteProductValues()
    {
        $this->deleteProductNo = $this->productAndImageDataHelper->getDeleteProductValuesNO();
        $this->deleteProductYes = $this->productAndImageDataHelper->getDeleteProductValuesYes();
    }

    /**
     * Delete Existing Images Based On Stock Code
     * @param ProductRepository $productDetail
     * @param array $objectDir
     * @param \Aws\S3\S3Client $client
     * @param $bucket
     * @param $object
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function setProductImageUsingStockCode($productDetail, array $objectDir, \Aws\S3\S3Client $client, $bucket, $object)
    {
        if ($this->masterImageRemove[$productDetail->getSku()]==true) {
            $existingMediaGalleryEntries = $productDetail->getMediaGalleryEntries();
            foreach ($existingMediaGalleryEntries as $key => $entry) {
                unset($existingMediaGalleryEntries[$key]);
            }
            $productDetail->setMediaGalleryEntries($existingMediaGalleryEntries);
            $this->productRepository->save($productDetail);
            $this->masterImageRemove[$productDetail->getSku()]=false;
        }
        $this->setProductImagesUsingStockCode($client, $bucket, $object, $productDetail);
    }

    /**
     * Set Images Based On Stock Code
     * @param \Aws\S3\S3Client $client
     * @param $bucket
     * @param $object
     * @param ProductRepository $product
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function setProductImagesUsingStockCode(\Aws\S3\S3Client $client, $bucket, $object, $product)
    {
        $variantType = $product->getStockCode();
        /** @var string $tmpDir */
        $tmpDir = $this->productAndImageDataHelper->getMediaDirTmpDir();
        /** create folder if it is not exists */
        $this->file->checkAndCreateFolder($tmpDir);
        /** @var string $newFileName */
        $newFileName = $tmpDir . "/" . baseName($client->getObjectUrl($bucket, $object["Key"]));

        /** read file from URL and copy it to the new destination */

        $result = $this->file->read($client->getObjectUrl($bucket, $object["Key"]), $newFileName);

        if ($result) {
            /** add saved file to the $product gallery */
            $product->addImageToMediaGallery($newFileName, null, false, false);
            $this->productRepository->save($product);
            if (!$this->isThumbnailExist($product)) {
                $this->productAndImageDataHelper->setThumbnailImage($product->getSku());
            }
            unlink($newFileName);
        }
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $productDetail
     * @param \Aws\S3\S3Client $client
     * @param $bucketName
     * @param $object
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function setProductImageUsingStockCodeCa(\Magento\Catalog\Api\Data\ProductInterface $productDetail, \Aws\S3\S3Client $client, $bucketName, $object)
    {
        $this->storeManager->setCurrentStore(self::ALL_STORE_VIEW_ID);
        $productDetail->setStoreId(self::ALL_STORE_VIEW_ID);
        if ($this->checkImageExist[$productDetail->getSku()]) {
            $productDetail->setCustomAttribute('image_custom_urls', urldecode($client->getObjectUrl($bucketName, $object["Key"])));
            $this->productRepository->save($productDetail);
            $this->checkImageExist[$productDetail->getSku()] = false;
        } else {
            $newImageCustomUrls=$productDetail->getImageCustomUrls() . "," . urldecode($client->getObjectUrl($bucketName, $object["Key"]));
            $productDetail->setCustomAttribute('image_custom_urls', $newImageCustomUrls);
            $this->productRepository->save($productDetail);
        }
    }

    /**
     * @param $product
     * @param $imageUrls
     */
    public function setProductImageUsingStockCodeCac($product, $imageUrls)
    {
        try {
            $stores=$this->storeManager->getStores(true);
            foreach ($stores as $store) {
                $this->storeManager->setCurrentStore($store->getId());
                $product->setStoreId( $this->storeManager->getStore()->getId());
                $product->setCustomAttribute('image_custom_urls', $imageUrls);
                $this->productRepository->save($product);
            }
            echo "Image url assigned for stock-code: " . $product['stock_code'];
        } catch (\Exception $exception) {
            $this->logger->critical($exception->getMessage());
        }
    }
}
