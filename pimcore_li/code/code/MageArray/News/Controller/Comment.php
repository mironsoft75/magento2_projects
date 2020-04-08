<?php

namespace MageArray\News\Controller;

use MageArray\News\Helper\Data;
use MageArray\News\Model\Customerdata;
use Magento\Framework\App\Action\Action as Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Escaper;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;

/**
 * Class Comment
 * @package MageArray\News\Controller
 */
abstract class Comment extends Action
{
    /**
     *
     */
    const XML_PATH_EMAIL_RECIPIENT = 'magearray/comments/admin_email';
    /**
     * @var Data
     */
    protected $_dataHelper;
    /**
     * @var Escaper
     */
    protected $_escaper;
    /**
     * @var TransportBuilder
     */
    protected $_transportBuilder;
    /**
     * @var StateInterface
     */
    protected $_inlineTranslation;
    /**
     * @var Customerdata
     */
    protected $_customerdata;
    /**
     * @var RemoteAddress
     */
    protected $_remoteAddress;
    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Comment constructor.
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param RemoteAddress $remoteAddress
     * @param Customerdata $customerdata
     * @param Escaper $escaper
     * @param Data $dataHelper
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        RemoteAddress $remoteAddress,
        Customerdata $customerdata,
        Escaper $escaper,
        Data $dataHelper,
        \MageArray\News\Model\NewspostFactory $newspostFactory,
        \MageArray\News\Model\NewscommentFactory $newscommentFactory
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_transportBuilder = $transportBuilder;
        $this->_inlineTranslation = $inlineTranslation;
        $this->_remoteAddress = $remoteAddress;
        $this->_customerdata = $customerdata;
        $this->_dataHelper = $dataHelper;
        $this->_escaper = $escaper;
        $this->_newspostFactory = $newspostFactory;
        $this->_newscommentFactory = $newscommentFactory;
        parent::__construct($context);
    }
}
