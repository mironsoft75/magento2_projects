<?php

namespace MageArray\News\Block\Top;

use MageArray\News\Helper\Data;
use Magento\Customer\Model\Url;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Link
 * @package MageArray\News\Block\Top
 */
class Sitemap extends \Magento\Framework\View\Element\Html\Link
{

    /**
     * @var HttpContext
     */
    protected $httpContext;

    /**
     * @var Url
     */
    protected $_customerUrl;

    /**
     * @var
     */
    protected $_postDataHelper;
    /**
     * @var Data
     */
    protected $_dataHelper;

    /**
     * Link constructor.
     * @param Context $context
     * @param HttpContext $httpContext
     * @param Url $customerUrl
     * @param Data $dataHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        HttpContext $httpContext,
        Url $customerUrl,
        Data $dataHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->httpContext = $httpContext;
        $this->_customerUrl = $customerUrl;
        $this->_dataHelper = $dataHelper;
    }
    /**
     * @return string
     */
    public function getHref()
    {
        $path = $this->_dataHelper->getSitemapUrlPath();
        return $this->getUrl() . $path;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return "SiteMap";
    }
}
