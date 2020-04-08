<?php

namespace MageArray\News\Model\Newspost;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\UrlInterface;

/**
 * Class Image
 * @package MageArray\News\Model\Newspost
 */
class Image
{
    /**
     * @var string
     */
    protected $subDir = 'magearray/news/newspost';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * Image constructor.
     * @param UrlInterface $urlBuilder
     * @param Filesystem $fileSystem
     */
    public function __construct(
        UrlInterface $urlBuilder,
        Filesystem $fileSystem
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->fileSystem = $fileSystem;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->urlBuilder
                ->getBaseUrl(
                    ['_type' => UrlInterface::URL_TYPE_MEDIA]
                ) . $this->subDir . '/image/';
    }

    /**
     * @return string
     */
    public function getBaseDir()
    {
        return $this->fileSystem
            ->getDirectoryWrite(DirectoryList::MEDIA)
            ->getAbsolutePath($this->subDir . '/image/');
    }
}
