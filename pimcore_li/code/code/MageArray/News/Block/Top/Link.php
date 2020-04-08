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
class Link extends \Magento\Framework\View\Element\Html\Link
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
        if ($this->isEnable()) {
            $listUrl = $this->_dataHelper
                ->getStoreConfig('magearray_news/general/list_url');
            return $this->getUrl() . $listUrl;
        } else {
            return "";
        }
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        if ($this->isEnable()) {
            return $this->_dataHelper
                ->getStoreConfig('magearray_news/general/top_menu_title');
        } else {
            return "";
        }
    }

    public function isEnable()
    {
        return $this->_dataHelper
            ->getStoreConfig('magearray_news/general/active');
    }
}
