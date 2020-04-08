<?php

namespace WeltPixel\GoogleCards\Block;

/**
 * Class Breadcrumbs
 * @package WeltPixel\GoogleCards\Block
 */
class Breadcrumbs extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Catalog\Model\Session
     */
    protected $_catalogSession;

    /**
     * Breadcrumbs constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Catalog\Model\Session $catalogSession
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\Session $catalogSession,
        array $data = []
    )
    {
        $this->_catalogSession = $catalogSession;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve Breadcrumb data from session
     * @return mixed
     */
    public function getCrumbs()
    {
        return $this->_catalogSession->getBreadcrumbData();
    }


}
