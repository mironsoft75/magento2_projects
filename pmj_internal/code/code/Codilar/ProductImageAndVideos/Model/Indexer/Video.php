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
use Magento\Framework\Filesystem\Io\File;
use Magento\Store\Model\StoreManagerInterface;
use Thai\S3\Helper\Data as DataHelper;

/**
 * Class Video
 * @package Codilar\ProductImageAndVideos\Model\Indexer
 */
class Video implements \Magento\Framework\Indexer\ActionInterface, \Magento\Framework\Mview\ActionInterface
{
    const NO_SELECTION = "no_selection";
    const VARIANT_TYPE = "variant_type";
    const STOCK_CODE = "stock_code";
    const STOCK_CODE_CA = "stock_code_ca";
    const ALL_STORE_VIEW_ID = 0;

    /**
     * @var $file
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
    /**
     * @var $client
     */
    private $client;
    /**
     * @var string $deleteProductYes
     */
    private $deleteProductYes;
    /**
     * @var string $deleteProductNo
     */
    private $deleteProductNo;
    /**
     * @var $productAndImageDataHelper
     */
    private $productAndImageDataHelper;
    /**
     * @var DataHelper
     */
    private $helper;
    /**
     * @var CollectionFactory
     */
    private $productCollectionFactory;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var Filesystem
     */
    private $filesystem;

    private $videoRemove = [];
    private $action;
    private $storeManager;
    private $checkVideoExist = [];

    /**
     * Video constructor.
     * @param Filesystem $filesystem
     * @param ProductRepository $productRepository
     * @param CollectionFactory $productCollectionFactory
     * @param DataHelper $helper
     * @param File $file
     * @param Data $productAndImageDataHelper
     * @param Action $action
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Filesystem $filesystem,
        ProductRepository $productRepository,
        CollectionFactory $productCollectionFactory,
        DataHelper $helper,
        File $file,
        Data $productAndImageDataHelper,
        Action $action,
        StoreManagerInterface $storeManager
    ) {
        $this->filesystem = $filesystem;
        $this->productRepository = $productRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->helper = $helper;
        $this->file = $file;
        $this->productAndImageDataHelper = $productAndImageDataHelper;
        $this->action = $action;
        $this->storeManager = $storeManager;
    }

    /*
    * Used by mview, allows process indexer in the "Update on schedule" mode
    */
    public function execute($ids)
    {
    }

    /*
     * Will take all of the data and reindex
     * Will run when reindex via command line
     */
    /**
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function executeFull()
    {
        /** @var array $stockCode */
        $stockCode=[];
        /** @var array $productSku */
        $productSku=[];
        $selectAttribute = $this->productAndImageDataHelper->getSelectAttribute();
        $this->client = $this->productAndImageDataHelper->setS3Credentials();
        $this->bucketName = $this->helper->getBucket();
        $this->generatedFolderName = $this->productAndImageDataHelper->getGeneratedFolderName();
//        $this->setDeleteProductValues();
//        $productCollection = $this->productCollectionFactory->create();
//        $productCollection->addAttributeToSelect('sku');
//        foreach ($productCollection as $product) {
//            $this->videoRemove[$product->getSku()]["video_url"] = true;
//            $this->videoRemove[$product->getSku()]["small_video_url"] = true;
//            $this->videoRemove[$product->getSku()]["medium_video_url"] = true;
//            $this->videoRemove[$product->getSku()]["large_video_url"] = true;
//            $this->videoRemove[$product->getSku()]["video_thumbnail_url"] = true;
//        }
//        $iterator = $this->client->getIterator('ListObjects', [
//            'Bucket' => $this->bucketName
//        ]);
//        if ($selectAttribute == self::VARIANT_TYPE) {
//            $productCollection = $this->productCollectionFactory->create();
//            $productCollection->addAttributeToSelect('sku')
//                ->addFieldToFilter('delete_product', ['eq' => $this->deleteProductNo]);
//            foreach ($iterator as $object) {
//                $objectDir = $this->productAndImageDataHelper->getObjectDirectory($object);
//                foreach ($productCollection as $product) {
//                    if (count($objectDir) > 1) {
//                        if ($this->productAndImageDataHelper->isVariantTypeExist($product, $object)) {
//                            if ($this->productAndImageDataHelper->isVideo(
//                                $object,
//                                $this->client,
//                                $this->bucketName
//                            )) {
//                                // Videos
//                                $this->setVideoUrl(
//                                    $product,
//                                    $this->client,
//                                    $this->bucketName,
//                                    $object,
//                                    $selectAttribute
//                                );
//                            } else {
//                                // Image
//                                continue;
//                            }
//                        }
//                    }
//                }
//            }
//        }
//        elseif ($selectAttribute == self::STOCK_CODE) {
//            $productCollection = $this->productCollectionFactory->create();
//            foreach ($iterator as $object) {
//                $objectDir = $this->productAndImageDataHelper->getObjectDirectory($object);
//                foreach ($productCollection as $product) {
//                    if (count($objectDir) > 1) {
//                        if ($this->productAndImageDataHelper->isStockCodeExist($product, $object)) {
//                            if ($this->productAndImageDataHelper->isVideo($object)) {
//                                // Videos
//                                $this->setVideoUrl(
//                                    $product,
//                                    $this->client,
//                                    $this->bucketName,
//                                    $object,
//                                    $selectAttribute
//                                );
//                            } else {
//                                // Image
//                                continue;
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
                ['attribute'=> 'video_url','null' => true],
                ['attribute'=> 'video_url','eq' => ''],
                ['attribute'=> 'video_url','eq' => 'NULL']

            ],
                '',
                'left'
            );
            $productCollection->addAttributeToSelect("*");
            foreach ($productCollection as $product) {
                $this->checkVideoExist[$product->getSku()] = true;
            }

            foreach ($productCollection as $product) {
                $iterator = $this->client->getIterator('ListObjects', [
                    'Bucket' =>$this->bucketName,
                    "Prefix" =>$product['stock_code'] . "/"
                ]);
                foreach ($iterator as $object) {
                    $fileType=$this->productAndImageDataHelper->checkFileType($object, $this->client, $this->bucketName);
                    if ($fileType == "video") {
                        // Videos
                        $this->setVideoUrlUsingStockCodeCA(
                            $product,
                            $this->client,
                            $this->bucketName,
                            $object
                        );
                    } else {

                        // Image\unwanted file
                        continue;
                    }
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
     * Media directory name for the temporary file storage
     * pub/media/tmp
     *
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    protected function getMediaDirTmpDir()
    {
        return $this->productAndImageDataHelper->directoryList->getPath(DirectoryList::MEDIA) . DIRECTORY_SEPARATOR . 'tmp';
    }

    /**
     * @param $product
     * @param $newFileName
     * @param $variantType
     * @param \Aws\S3\S3Client $client
     * @param $sourceFileName
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Exception
     */
    public function createVideos($product, $newFileName, $variantType, \Aws\S3\S3Client $client, $sourceFileName, $selectAttribute)
    {
        $newVideoName = $this->getNewVideoName($sourceFileName);
        $opts = [
            'library' => 'ffmpeg',
            'videotypes' => ['video/mp4', 'video/x-flv'],
            'input' => '',
            'name' => $newVideoName,
            'output' => '.',
            'timespan' => '1',
            'width' => 240,
            'verbose' => false,
            'poster' => true,
            'delete' => true
        ];
        $params = [
            'library' => 'ffmpeg', // the ffmpeg command - full path if needs be
            'input' => null,     // input video file - specified as command line parameter
            'output' => __DIR__,  // The output directory
            'timespan' => 10,       // seconds between each thumbnail
            'thumbWidth' => 120,      // thumbnail width
            'spriteWidth' => 10,       // number of thumbnails per row in sprite sheet
            'videotypes' => ['video/mp4']
        ];
        $generatedFolderName = $this->productAndImageDataHelper->getGeneratedFolderName();
        $timeToGenerateThumbnail = $this->productAndImageDataHelper->getTimeToGenerateThumbnail();
        $generatedFolderUrl = $client->getObjectUrl($this->bucketName, $generatedFolderName);
        $this->storeManager->setCurrentStore(self::ALL_STORE_VIEW_ID);
        $rootpath = $this->filesystem->
        getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath();
        if ($selectAttribute == self::VARIANT_TYPE) {
            $productCollection = $this->productCollectionFactory->create();
            $childCollection = $productCollection->addAttributeToSelect('*')
                ->addFieldToFilter('delete_product', ['eq' => $this->deleteProductYes]);
            $childProductCollection = $childCollection->addFieldToFilter('variant_type', $variantType);
        }
        $opts['input'] = $newFileName;
        // process input parameters
        if (isset($opts['library'])) {
            $params['library'] = $opts['library'];
        }
        $params['input'] = escapeshellarg($opts['input']);
        if (isset($opts['output'])) {
            $params['output'] = realpath($opts['output']);
        }
        if (isset($opts['timespan']) && (int)$opts['timespan']) {
            $params['timespan'] = $opts['timespan'];
        }
        if (isset($opts['width']) && (int)$opts['width']) {
            $params['thumbWidth'] = $opts['width'];
        }

        if (isset($opts['videotypes']) && is_array($opts['videotypes'])) {
            $params['videotypes'] = $opts['videotypes'];
        }

        $commands = [
            'details' => $params['library'] . ' -i %s 2>&1',
            'poster' => $params['library'] . ' -ss %d -i %s -y -vframes 1 "%s/%s-poster.jpg" 2>&1',
            'thumbs' => $params['library'] . ' -ss %0.04f -i %s -y -an -sn -vsync 0 -q:v 5 -threads 1 '
                . '-vf scale=%d:-1,select="not(mod(n\\,%d))" "%s/thumbnails/%s-%%04d.jpg" 2>&1'
        ];

        // sanity checks
        if (!is_readable($opts['input'])) {
            if (filter_var($opts['input'], FILTER_VALIDATE_URL)) {
                if ($this->checkurl($opts['input'], $params['videotypes']) === false) {
                    throw new \Exception("Cannot read the url file '{$opts['input']}'");
                }
            } else {
                throw new \Exception("Cannot read the input file '{$opts['input']}'");
            }
        }
        if (!is_writable($params['output'])) {
            throw new \Exception("Cannot write to output directory '{$opts['output']}'");
        }
        if (!file_exists($params['output'] . '/thumbnails')) {
            if (!mkdir($params['output'] . '/thumbnails')) {
                throw new \Exception("Could not create thumbnail output directory '{$params['output']}/thumbnails'");
            }
        }

        $details = shell_exec(sprintf($commands['details'], $params['input']));
        if ($details === null || !preg_match('/^(?:\s+)?' . $params['library'] . ' version ([^\s,]*)/i', $details)) {
            throw new \Exception('Cannot find ' . $params['library'] .
                ' - try specifying the path in the $params variable');
        }

        // determine some values we need
        $time = $fps = [];
        preg_match('/Duration: ((\d+):(\d+):(\d+))\.\d+, start: ([^,]*)/is', $details, $time);
        preg_match('/\b(\d+(?:\.\d+)?) fps\b/', $details, $fps);

        $duration = ($time[2] * 3600) + ($time[3] * 60) + $time[4];
        $start = $time[5];
        $fps = $fps[1];

        $name = (isset($opts['name'])) ? $opts['name'] : strtolower(substr(
            basename($opts['input']),
            0,
            strrpos(basename($opts['input']), '.')
        ));
        $dir = $rootpath;
        $fold = $opts['name'];

        if (!file_exists($dir . $fold)) {
            mkdir($dir . $fold, 0777);
        }
        $folder = $dir . $fold . '/';
        /**
         * Generate 360 video
         */
        exec('ffmpeg -i ' . $opts['input'] . ' -vf scale=-480:360 ' . $folder . $opts['name'] . '-360.mp4');
        if ($this->videoRemove[$product->getSku()]['small_video_url'] == true) {
            $product->setCustomAttribute('small_video_url', $generatedFolderUrl . $variantType . '/' . $opts['name'] . '-360.mp4');
            $this->videoRemove[$product->getSku()]['small_video_url'] = false;
        } else {
            $existingSmallVideoUrl = $product->getSmallVideoUrl();
            $newSmallVideoUrl = $generatedFolderUrl . $variantType . '/' . $opts['name'] . '-360.mp4';
            $smallVideoUrl = $existingSmallVideoUrl . ',' . $newSmallVideoUrl;
            $product->setCustomAttribute('small_video_url', $smallVideoUrl);
        }
        $result = $this->client->putObject([
            'Bucket' => $this->bucketName,
            'Key' => $this->generatedFolderName . $variantType . '/' . $opts['name'] . '-360.mp4',
            'SourceFile' => $folder . $opts['name'] . '-360.mp4',
            'ACL' => 'public-read'
        ]);
        unlink($folder . $opts['name'] . '-360.mp4');

        //generate 480p video
        exec('ffmpeg -i ' . $opts['input'] . ' -vf scale=-858:480 ' . $folder . $opts['name'] . '-480.mp4');
        if ($this->videoRemove[$product->getSku()]['medium_video_url'] == true) {
            $product->setCustomAttribute('medium_video_url', $generatedFolderUrl . $variantType . '/' . $opts['name'] . '-480.mp4');
            $this->videoRemove[$product->getSku()]['medium_video_url'] = false;
        } else {
            $existingMediumVideoUrl = $product->getMediumVideoUrl();
            $newMediumVideoUrl = $generatedFolderUrl . $variantType . '/' . $opts['name'] . '-480.mp4';
            $mediumVideoUrl = $existingMediumVideoUrl . ',' . $newMediumVideoUrl;
            $product->setCustomAttribute('medium_video_url', $mediumVideoUrl);
        }
        $result = $this->client->putObject([
            'Bucket' => $this->bucketName,
            'Key' => $this->generatedFolderName . $variantType . '/' . $opts['name'] . '-480.mp4',
            'SourceFile' => $folder . $opts['name'] . '-480.mp4',
            'ACL' => 'public-read'
        ]);
        unlink($folder . $opts['name'] . '-480.mp4');
        //generate 720p video
        exec('ffmpeg -i ' . $opts['input'] . ' -vf scale=-1280:720 ' . $folder . $opts['name'] . '-720.mp4');
        exec('ffmpeg -i ' . $opts['input'] . ' -vf scale=-858:480 ' . $folder . $opts['name'] . '-480.mp4');
        if ($this->videoRemove[$product->getSku()]['large_video_url'] == true) {
            $product->setCustomAttribute('large_video_url', $generatedFolderUrl . $variantType . '/' . $opts['name'] . '-720.mp4');
            $this->videoRemove[$product->getSku()]['large_video_url'] = false;
        } else {
            $existingLargeVideoUrl = $product->getLargeVideoUrl();
            $newLargeVideoUrl = $generatedFolderUrl . $variantType . '/' . $opts['name'] . '-720.mp4';
            $largeVideoUrl = $existingLargeVideoUrl . ',' . $newLargeVideoUrl;
            $product->setCustomAttribute('large_video_url', $largeVideoUrl);
        }
        $result = $this->client->putObject([
            'Bucket' => $this->bucketName,
            'Key' => $this->generatedFolderName . $variantType . '/' . $opts['name'] . '-720.mp4',
            'SourceFile' => $folder . $opts['name'] . '-720.mp4',
            'ACL' => 'public-read'
        ]);
        unlink($folder . $opts['name'] . '-720.mp4');
        if (isset($opts['poster']) && $opts['poster'] === true) {
            shell_exec(sprintf($commands['poster'], $timeToGenerateThumbnail, $opts['input'], $folder, $name));
        }
        if ($this->videoRemove[$product->getSku()]['video_thumbnail_url'] == true) {
            $product->setCustomAttribute('video_thumbnail_url', $generatedFolderUrl . $variantType . '/' . $opts['name'] . '-poster.jpg');
            $this->videoRemove[$product->getSku()]['video_thumbnail_url'] = false;
        } else {
            $existingVideoThumbnailUrl = $product->getVideoThumbnailUrl();
            $newVideoThumbnailUrl = $generatedFolderUrl . $variantType . '/' . $opts['name'] . '-poster.jpg';
            $videoThumbnailUrl = $existingVideoThumbnailUrl . ',' . $newVideoThumbnailUrl;
            $product->setCustomAttribute('video_thumbnail_url', $videoThumbnailUrl);
        }
        $result = $this->client->putObject([
            'Bucket' => $this->bucketName,
            'Key' => $this->generatedFolderName . $variantType . '/' . $opts['name'] . '-poster.jpg',
            'SourceFile' => $folder . $opts['name'] . '-poster.jpg',
            'ACL' => 'public-read'
        ]);
        $thumbnailPath = $folder . $opts['name'] . '-poster.jpg';
        $thumbnail_url = $product->getData('thumbnail');
        if ($this->videoRemove[$product->getSku()]['video_url'] == true) {
            $product->setCustomAttribute('video_url', $sourceFileName);
            $this->videoRemove[$product->getSku()]['video_url'] = false;
        } else {
            $existingVideoUrl = $product->getVideoUrl();
            $videoUrl = $existingVideoUrl . ',' . $sourceFileName;
            $product->setCustomAttribute('video_url', $videoUrl);
        }
        unlink($opts['input']);
        if ($thumbnail_url == self::NO_SELECTION) {
            $product->addImageToMediaGallery(
                $thumbnailPath,
                null,
                false,
                false
            );
            $this->productRepository->save($product);
            $this->productAndImageDataHelper->setThumbnailImage($product->getSku());
            if ($selectAttribute == self::VARIANT_TYPE) {
                $this->setImageForChildProduct($variantType, $thumbnailPath);
            } else {
                unlink($thumbnailPath);
            }
        } else {
            $this->productRepository->save($product);
        }

        if ($selectAttribute == self::VARIANT_TYPE) {
            $this->setVideoUrlForChildProducts($sourceFileName, $childProductCollection, $generatedFolderUrl, $variantType, $opts, $name);
        }
    }

    /**
     * @param $variantType
     * @param $thumbnailPath
     * @return mixed
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function setImageForChildProduct($variantType, $thumbnailPath)
    {
        $productCollection = $this->productCollectionFactory->create();
        $childCollection = $productCollection->addAttributeToSelect('sku')
            ->addFieldToFilter('delete_product', ['eq' => $this->deleteProductYes]);
        $childProductCollection = $childCollection->addFieldToFilter('variant_type', $variantType);
        foreach ($childProductCollection as $childProduct) {
            $childProduct = $this->productRepository->get($childProduct->getSku());
            $thumbnail_url = $childProduct->getData('thumbnail');
            if ($thumbnail_url == self::NO_SELECTION) {
                $childProduct->addImageToMediaGallery(
                    $thumbnailPath,
                    null,
                    false,
                    false
                );
                $this->productRepository->save($childProduct);
                $this->productAndImageDataHelper->setThumbnailImage($childProduct->getSku());
            }
        }
        unlink($thumbnailPath);
    }

    /**
     * Check url & Content-Type (eg. 'video/mp4')
     * @param $url
     * @param $videotypes
     * @return bool
     */
    public function checkurl($url, $videotypes)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        return ($data !== false && $httpcode === 200 && in_array($contentType, $videotypes));
    }

    /**
     * @param $product
     * @param \Aws\S3\S3Client $client
     * @param $bucket
     * @param $object
     * @param $selectAttribute
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function setVideoUrl($product, \Aws\S3\S3Client $client, $bucket, $object, $selectAttribute)
    {
        $product = $this->productRepository->get($product->getSku(), false, self::ALL_STORE_VIEW_ID);
        $sourceFileName = $client->getObjectUrl($bucket, $object["Key"]);
        $tmpDir = $this->productAndImageDataHelper->getMediaDirTmpDir();
        /** create folder if it is not exists */
        $this->file->checkAndCreateFolder($tmpDir);
        /** @var string $newFileName */
        $newFileName = $tmpDir . "/" . baseName($client->getObjectUrl($bucket, $object["Key"]));
        /** read file from URL and copy it to the new destination */
        $result = $this->file->read($client->getObjectUrl($bucket, $object["Key"]), $newFileName);
        $variantType = $product->getVariantType();
        $stockCode = $product->getStockCode();
        if ($selectAttribute == self::VARIANT_TYPE) {
            $this->createVideos($product, $newFileName, $variantType, $this->client, $sourceFileName, $selectAttribute);
        } elseif ($selectAttribute == self::STOCK_CODE) {
            $this->createVideos($product, $newFileName, $stockCode, $this->client, $sourceFileName, $selectAttribute);
        }
    }

    /**
     * Using Variant Type
     * @param $sourceFileName
     * @param $childProductCollection
     * @param $generatedFolderUrl
     * @param $variantType
     * @param array $opts
     * @param $name
     * @throws \Exception
     */
    public function setVideoUrlForChildProducts($sourceFileName, $childProductCollection, $generatedFolderUrl, $variantType, array $opts, $name)
    {
        foreach ($childProductCollection as $childProduct) {
            try {
                if ($this->videoRemove[$childProduct->getSku()]['small_video_url'] == true) {
                    $childProduct->setCustomAttribute('small_video_url', $generatedFolderUrl . $variantType . '/' . $opts['name'] . '-360.mp4');
                    $this->videoRemove[$childProduct->getSku()]['small_video_url'] = false;
                } else {
                    $existingSmallVideoUrl = $childProduct->getSmallVideoUrl();
                    $newSmallVideoUrl = $generatedFolderUrl . $variantType . '/' . $opts['name'] . '-360.mp4';
                    $smallVideoUrl = $existingSmallVideoUrl . ',' . $newSmallVideoUrl;
                    $childProduct->setCustomAttribute('small_video_url', $smallVideoUrl);
                }
                if ($this->videoRemove[$childProduct->getSku()]['medium_video_url'] == true) {
                    $childProduct->setCustomAttribute('medium_video_url', $generatedFolderUrl . $variantType . '/' . $opts['name'] . '-480.mp4');
                    $this->videoRemove[$childProduct->getSku()]['medium_video_url'] = false;
                } else {
                    $existingMediumVideoUrl = $childProduct->getMediumVideoUrl();
                    $newMediumVideoUrl = $generatedFolderUrl . $variantType . '/' . $opts['name'] . '-480.mp4';
                    $mediumVideoUrl = $existingMediumVideoUrl . ',' . $newMediumVideoUrl;
                    $childProduct->setCustomAttribute('medium_video_url', $mediumVideoUrl);
                }
                if ($this->videoRemove[$childProduct->getSku()]['large_video_url'] == true) {
                    $childProduct->setCustomAttribute('large_video_url', $generatedFolderUrl . $variantType . '/' . $opts['name'] . '-720.mp4');
                    $this->videoRemove[$childProduct->getSku()]['large_video_url'] = false;
                } else {
                    $existingLargeVideoUrl = $childProduct->getLargeVideoUrl();
                    $newLargeVideoUrl = $generatedFolderUrl . $variantType . '/' . $opts['name'] . '-720.mp4';
                    $largeVideoUrl = $existingLargeVideoUrl . ',' . $newLargeVideoUrl;
                    $childProduct->setCustomAttribute('large_video_url', $largeVideoUrl);
                }
                if ($this->videoRemove[$childProduct->getSku()]['video_thumbnail_url'] == true) {
                    $childProduct->setCustomAttribute('video_thumbnail_url', $generatedFolderUrl . $variantType . '/' . $opts['name'] . '-poster.jpg');
                    $this->videoRemove[$childProduct->getSku()]['video_thumbnail_url'] = false;
                } else {
                    $existingVideoThumbnailUrl = $childProduct->getVideoThumbnailUrl();
                    $newVideoThumbnailUrl = $generatedFolderUrl . $variantType . '/' . $opts['name'] . '-poster.jpg';
                    $videoThumbnailUrl = $existingVideoThumbnailUrl . ',' . $newVideoThumbnailUrl;
                    $childProduct->setCustomAttribute('video_thumbnail_url', $videoThumbnailUrl);
                }
                if ($this->videoRemove[$childProduct->getSku()]['video_url'] == true) {
                    $childProduct->setCustomAttribute("video_url", $sourceFileName);
                    $this->videoRemove[$childProduct->getSku()]['video_url'] = false;
                } else {
                    $existingVideoUrl = $childProduct->getVideoUrl();
                    $videoUrl = $existingVideoUrl . ',' . $sourceFileName;
                    $childProduct->setCustomAttribute("video_url", $videoUrl);
                }
                $this->productRepository->save($childProduct);
            } catch (\Exception $e) {
                throw new \Exception('Error in saving Product');
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
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function getNewVideoName($name)
    {
        try {
            $videoName = explode("/", $name);
            $newVideoName = (explode(".", end($videoName))[0]);
            return $newVideoName;
        } catch (\Exception $e) {
            throw new \Exception("Error in getting Video Name");
        }
    }

    /**
     * Download video and call createVideosUsingStockCodeCa function to generate video thumbnail image
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param \Aws\S3\S3Client $client
     * @param $bucketName
     * @param $object
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function setVideoUrlUsingStockCodeCA(\Magento\Catalog\Api\Data\ProductInterface $product, \Aws\S3\S3Client $client, $bucketName, $object)
    {
//        if ($this->checkVideoExist[$product->getSku()]) {
//            if ($product->getVideoUrl()) {
//                return;
//            }
//        }

        /** @var string $sourceFileName */
        $sourceFileName =$client->getObjectUrl($bucketName, $object["Key"]);
        $tmpDir = $this->productAndImageDataHelper->getMediaDirTmpDir();
        /** create folder if it is not exists */
        $this->file->checkAndCreateFolder($tmpDir);
        /** @var string $newFileName */
        $newFileName = $tmpDir . "/" . baseName($client->getObjectUrl($bucketName, $object["Key"]));
        /** read file from URL and copy it to the new destination */
        $result = $this->file->read($client->getObjectUrl($bucketName, $object["Key"]), $newFileName);
        if ($result) {
            $this->createVideosUsingStockCodeCa($product, $newFileName, $this->client, $sourceFileName);
        }
    }

    /**
     * Generate Thumbnail image and save data in custom attribute
     * @param $product
     * @param $newFileName
     * @param $client
     * @param $sourceFileName
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function createVideosUsingStockCodeCa($product, $newFileName, $client, $sourceFileName)
    {

            /** @var string $stockCode */
            $stockCode = $product->getStockCode();
            /** @var string $newVideoName */
            $newVideoName = $this->getNewVideoName($sourceFileName);
            $params = [
                'library' => 'ffmpeg', // the ffmpeg command - Give full path if needs to be
            ];
            $generatedFolderName = $this->productAndImageDataHelper->getGeneratedFolderName();
            $timeToGenerateThumbnail = $this->productAndImageDataHelper->getTimeToGenerateThumbnail();
            $generatedFolderUrl = $client->getObjectUrl($this->bucketName, $generatedFolderName);
            $this->storeManager->setCurrentStore(self::ALL_STORE_VIEW_ID);
            $rootPath = $this->filesystem->
            getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath();

            if (!file_exists($rootPath . $stockCode)) {
                mkdir($rootPath . $stockCode, 0777);
            }
            $folder = $rootPath . $stockCode . '/';
            $commands = [
                'poster' => $params['library'] . ' -ss %d -i %s -y -vframes 1 "%s/%s-poster.jpg" 2>&1'
            ];
            if ($this->checkVideoExist[$product->getSku()]) {
                $product->setCustomAttribute('video_url', $sourceFileName);
                $this->checkVideoExist[$product->getSku()] = false;
                shell_exec(sprintf($commands['poster'], $timeToGenerateThumbnail, $newFileName, $folder, $stockCode));
                $newVideoThumbnailUrl = $generatedFolderUrl . $stockCode . '/' . $newVideoName . '-poster.jpg';
                $product->setCustomAttribute('video_thumbnail_url', $newVideoThumbnailUrl);
                $result = $this->client->putObject([
                    'Bucket' => $this->bucketName,
                    'Key' => $this->generatedFolderName . $stockCode . '/' . $newVideoName . '-poster.jpg',
                    'SourceFile' => $folder . $stockCode . '-poster.jpg',
                    'ACL' => 'public-read'
                ]);
            } else {
                $existingVideoUrl = $product->getVideoUrl();
                $videoUrl = $existingVideoUrl . ',' . $sourceFileName;
                $product->setCustomAttribute('video_url', $videoUrl);
                shell_exec(sprintf($commands['poster'], $timeToGenerateThumbnail, $newFileName, $folder, $stockCode));
                $newVideoThumbnailUrl = $generatedFolderUrl . $stockCode . '/' . $newVideoName . '-poster.jpg';
                $existingVideoThumbnailUrl = $product->getVideoThumbnailUrl();
                $videoThumbnailUrl = $existingVideoThumbnailUrl . ',' . $newVideoThumbnailUrl;
                $product->setCustomAttribute('video_thumbnail_url', $videoThumbnailUrl);
                $result = $this->client->putObject([
                    'Bucket' => $this->bucketName,
                    'Key' => $this->generatedFolderName . $stockCode . '/' . $newVideoName . '-poster.jpg',
                    'SourceFile' => $folder . $stockCode . '-poster.jpg',
                    'ACL' => 'public-read'
                ]);
            }
            $stores=$this->storeManager->getStores(true);
        foreach ($stores as $store) {
            $this->storeManager->setCurrentStore($store->getId());
            $product->setStoreId( $this->storeManager->getStore()->getId());
            $this->productRepository->save($product);
        }

        unlink($folder . $stockCode . '-poster.jpg');
            unlink($newFileName);
//        if (file_exists($rootPath . $stockCode)) {
//            rmdir($rootPath . $stockCode);
//        }
        echo "\n";
        echo " Video url and thumbnail generated for $sourceFileName \n ";
        echo "\n";
    }
}
