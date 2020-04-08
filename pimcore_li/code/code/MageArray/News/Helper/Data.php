<?php

namespace MageArray\News\Helper;

use MageArray\News\Model\Newspost;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\HTTP\Adapter\FileTransferFactory;
use Magento\Framework\Image\Factory;
use Magento\Framework\UrlInterface;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Data
 * @package MageArray\News\Helper
 */
class Data extends AbstractHelper
{
    /**
     *
     */
    const XML_PATH_ITEMS_PER_PAGE = 'magearray_news/general/postonlist';
    /**
     *
     */
    const XML_PATH_POST_SORT_ORDER = 'magearray_news/general/post_sorting';
    /**
     *
     */
    const XML_PATH_LIST_PAGE_LAYOUT = 'magearray_news/general/post_list_layout';
    /**
     *
     */
    const XML_PATH_COMMENTS_PER_PAGE = 'magearray_news/comments/commentcount';
    /**
     *
     */
    const XML_PATH_RELATED_POST_DISPLAY =
        'magearray_news/related_setting/related_posts/enabled';
    /**
     *
     */
    const XML_PATH_RELATED_POST_NUMBER =
        'magearray_news/related_setting/related_posts/number_of_posts';
    /**
     *
     */
    const XML_PATH_RELATED_PRODUCT_DISPLAY =
        'magearray_news/related_setting/related_products/enabled';
    /**
     *
     */
    const XML_PATH_RELATED_PRODUCT_NUMBER =
        'magearray_news/related_setting/related_products/number_of_products';
    /**
     *
     */
    const XML_PATH_RELATED_SHOW_CART =
        'magearray_news/related_setting/related_products/show_addtocart';
    /**
     *
     */
    const XML_PATH_RELATED_SHOW_WISHLIST =
        'magearray_news/related_setting/related_products/show_whishlist_icon';
    /**
     *
     */
    const XML_PATH_RELATED_SHOW_COMPARE =
        'magearray_news/related_setting/related_products/show_compare_icon';

    /**
     *
     */
    const MEDIA_PATH = 'magearray/news/image/';

    /**
     *
     */
    const MAX_FILE_SIZE = 1048576;

    /**
     *
     */
    const MIN_HEIGHT = 50;

    /**
     *
     */
    const MAX_HEIGHT = 1080;

    /**
     *
     */
    const MIN_WIDTH = 50;

    /**
     *
     */
    const MAX_WIDTH = 1920;
    /**
     *
     */
    const XML_PATH_SITEMAP = 'news/sitemap/show';

    /**
     * @var array
     */
    protected $_imageSize = [
        'minheight' => self::MIN_HEIGHT,
        'minwidth' => self::MIN_WIDTH,
        'maxheight' => self::MAX_HEIGHT,
        'maxwidth' => self::MAX_WIDTH,
    ];
    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $_mediaDirectory;

    /**
     * @var Filesystem
     */
    protected $_filesystem;

    /**
     * @var FileTransferFactory
     */
    protected $_httpFactory;

    /**
     * @var UploaderFactory
     */
    protected $_fileUploaderFactory;

    /**
     * @var File
     */
    protected $_ioFile;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    /**
     * @var Factory
     */
    protected $_imageFactory;

    /**
     * Data constructor.
     * @param Context $context
     * @param Filesystem $filesystem
     * @param FileTransferFactory $httpFactory
     * @param UploaderFactory $uploaderFactory
     * @param File $ioFile
     * @param StoreManagerInterface $storeManager
     * @param Factory $imageFactory
     */
    public function __construct(
        Context $context,
        Filesystem $filesystem,
        FileTransferFactory $httpFactory,
        UploaderFactory $uploaderFactory,
        File $ioFile,
        StoreManagerInterface $storeManager,
        Factory $imageFactory,
        \MageArray\News\Model\NewspostFactory $newsPostFactory,
        \MageArray\News\Model\NewscatFactory $newscatFactory
    ) {
        parent::__construct($context);
        $this->_scopeConfig = $this->scopeConfig;
        $this->_filesystem = $filesystem;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(
            DirectoryList::MEDIA
        );
        $this->_httpFactory = $httpFactory;
        $this->_fileUploaderFactory = $uploaderFactory;
        $this->_ioFile = $ioFile;
        $this->_storeManager = $storeManager;
        $this->_imageFactory = $imageFactory;
        $this->newsPostFactory = $newsPostFactory;
        $this->newscatFactory = $newscatFactory;
    }

    /**
     * @param $storePath
     * @return mixed
     */
    public function getStoreConfig($storePath)
    {
        $storeConfig = $this->_scopeConfig->getValue(
            $storePath,
            ScopeInterface::SCOPE_STORE
        );
        return $storeConfig;
    }

    /**
     * @return mixed
     */
    public function getPostPerPage()
    {
        return abs(
            (int)$this->_scopeConfig->getValue(
            self::XML_PATH_ITEMS_PER_PAGE,
            ScopeInterface::SCOPE_STORE
        )
        );
    }

    /**
     * @return mixed
     */
    public function getCommentsPerPage()
    {
        return abs(
            (int)$this->_scopeConfig->getValue(
            self::XML_PATH_COMMENTS_PER_PAGE,
            ScopeInterface::SCOPE_STORE
        )
        );
    }

    public function isEnable()
    {
        return $this->getStoreConfig('magearray_news/general/active');
    }

    /**
     * @return mixed
     */
    public function getSortOrder()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_POST_SORT_ORDER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function getRelatedProductDisplay()
    {
        return $this->_scopeConfig
            ->getValue(
                self::XML_PATH_RELATED_PRODUCT_DISPLAY,
                ScopeInterface::SCOPE_STORE
            );
    }

    /**
     * @return mixed
     */
    public function getRelatedProductNumber()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_RELATED_PRODUCT_NUMBER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function getRelatedPostDisplay()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_RELATED_POST_DISPLAY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getRelatedPostNumber()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_RELATED_POST_NUMBER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getRelatedShowCart()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_RELATED_SHOW_CART,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getRelatedShowWhishlist()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_RELATED_SHOW_WISHLIST,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getRelatedShowCompare()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_RELATED_SHOW_COMPARE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getListPagelayout()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_LIST_PAGE_LAYOUT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param $imageFile
     * @return bool
     */
    public function removeImage($imageFile)
    {
        $io = $this->_ioFile;
        $io->open(['path' => $this->getBaseDir()]);
        if ($io->fileExists($imageFile)) {
            return $io->rm($imageFile);
        }
        return false;
    }

    /**
     * @param Newspost $item
     * @param $width
     * @param null $height
     * @return bool|string
     */
    public function resize(Newspost $item, $width, $height = null)
    {
        if (!$item->getImage()) {
            return false;
        }

        if ($width < self::MIN_WIDTH || $width > self::MAX_WIDTH) {
            return false;
        }
        $width = (int)$width;

        if (!empty($height)) {
            if ($height < self::MIN_HEIGHT || $height > self::MAX_HEIGHT) {
                return false;
            }
            $height = (int)$height;
        }

        $imageFile = $item->getImage();
        $cacheDir = $this->getBaseDir() . 'cache' . '/' . $width;
        $cacheUrl = $this->getBaseUrlMedia() . 'cache' . '/' . $width . '/';

        $io = $this->_ioFile;
        $io->checkAndCreateFolder($cacheDir);
        $io->open(['path' => $cacheDir]);
        if ($io->fileExists($imageFile)) {
            return $cacheUrl . $imageFile;
        }

        try {
            $image = $this->_imageFactory
                ->create($this->getBaseDir() . '/' . $imageFile);
            $image->keepTransparency(true);
            $image->resize($width, $height);
            $image->save($cacheDir . '/' . $imageFile);
            return $cacheUrl . $imageFile;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param Newspost $item
     * @param $width
     * @param null $height
     * @return bool|string
     */
    public function resizeThumb(Newspost $item, $width, $height = null)
    {
        if (!$item->getImageThumb()) {
            return false;
        }

        if ($width < self::MIN_WIDTH || $width > self::MAX_WIDTH) {
            return false;
        }
        $width = (int)$width;

        if (!empty($height)) {
            if ($height < self::MIN_HEIGHT || $height > self::MAX_HEIGHT) {
                return false;
            }
            $height = (int)$height;
        }

        $imageFile = $item->getImageThumb();
        $cacheDir = $this->getBaseDir() . 'cache' . '/' . $width;
        $cacheUrl = $this->getBaseUrlMedia() . 'cache' . '/' . $width . '/';

        $io = $this->_ioFile;
        $io->checkAndCreateFolder($cacheDir);
        $io->open(['path' => $cacheDir]);
        if ($io->fileExists($imageFile)) {
            return $cacheUrl . $imageFile;
        }

        try {
            $image = $this->_imageFactory
                ->create($this->getBaseDir() . '/' . $imageFile);
            $image->keepTransparency(true);
            $image->resize($width, $height);
            $image->save($cacheDir . '/' . $imageFile);
            return $cacheUrl . $imageFile;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param $scope
     * @return bool|string
     * @throws InputException
     */
    public function uploadImage($scope)
    {
        $adapter = $this->_httpFactory->create();
        $adapter->addValidator(
            new \Zend_Validate_File_ImageSize($this->_imageSize)
        );
        $adapter->addValidator(
            new \Zend_Validate_File_FilesSize(['max' => self::MAX_FILE_SIZE])
        );

        if ($adapter->isUploaded($scope)) {
            // validate image
            if (!$adapter->isValid($scope)) {
                throw new InputException(__('Uploaded image is not valid.'));
            }

            $uploader = $this->_fileUploaderFactory
                ->create(['fileId' => $scope]);
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(false);
            $uploader->setAllowCreateFolders(true);

            if ($uploader->save($this->getBaseDir())) {
                return $uploader->getUploadedFileName();
            }
        }
        return false;
    }

    /**
     * @return string
     */
    public function getBaseDir()
    {
        $path = $this->_filesystem->getDirectoryRead(
            DirectoryList::MEDIA
        )->getAbsolutePath(self::MEDIA_PATH);
        return $path;
    }

    /**
     * @return string
     */
    public function getBaseUrlMedia()
    {
        return $this->_storeManager->getStore()
                ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . self::MEDIA_PATH;
    }

    /**
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    /**
     * @return mixed
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * @return mixed
     */
    public function getShowSitemap()
    {
        return $this->_scopeConfig
            ->getValue(
                self::XML_PATH_SHOW_SITEMAP,
                ScopeInterface::SCOPE_STORE
            );
    }
    /**
     * @return mixed
     */
    public function getSitemapUrlPath()
    {
        return self::XML_PATH_SITEMAP;
    }

    /**
     * get post list
     * @param null $type
     * @param null $id
     * @return array|string
     */
    public function getNewsPostList()
    {
        $list = '';
        $posts = $this->newsPostFactory->create();
        $categoryModel = $this->newsPostFactory->create();
        $now = date('Y-m-d');
        $list = $posts->getCollection()->setActiveFilter(true)->setPostFilter()
            ->setStoreFilter($this->_storeManager->getStore()->getId())
            ->addFieldToFilter('publish_date', ['lteq' => $now]);
        if (count($list) > 0) {
            return $list;
        }
        return '';
    }

    /**
     * get url by post
     * @param $post
     * @return string
     */
    public function getUrlByPost($post)
    {
        $urlKey = '';
        if ($post->getUrlKey()) {
            $urlPrefix = $this->getStoreConfig('magearray_news/general/url_prefix');
            $urlSuffix = $this->getStoreConfig('magearray_news/general/url_suffix');

            if ($urlPrefix) {
                $urlKey .= $urlPrefix . '/';
            }
            $urlKey .= $post->getUrlKey();
            if ($urlSuffix) {
                $urlKey .= "." . $urlSuffix;
            }
        }

        return $this->_urlBuilder->getUrl($urlKey);
    }

    public function getCategoryList()
    {
        $category = $this->newscatFactory->create();
        $list = $category->getCollection()->setActiveFilter(true)
            ->setStoreFilter($this->getStoreId());
        if (count($list) > 0) {
            return $list;
        }
        return '';
    }

    public function getCatUrl($catUrl)
    {
        $catPrefix = $this->getStoreConfig('magearray_news/general/cat_prefix');
        $urlSuffixConfig = $this->getStoreConfig('magearray_news/general/url_suffix');
        $urlSuffix = "";
        if (!empty($urlSuffixConfig) && $urlSuffixConfig != "") {
            $urlSuffix = '.' . $urlSuffixConfig;
        }
        return $this->_urlBuilder->getUrl() . $catPrefix . '/' . $catUrl . $urlSuffix;
    }
}
