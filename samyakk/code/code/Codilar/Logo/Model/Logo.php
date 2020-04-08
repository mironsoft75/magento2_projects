<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Logo\Model;

use Codilar\Logo\Api\Data\LogoInterface;
use Codilar\Logo\Helper\Data;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class Logo extends AbstractModel implements LogoInterface
{
    /**
     * @var Data
     */
    private $helper;

    /**
     * Logo constructor.
     * @param Context $context
     * @param Data $helper
     * @param Registry $registry
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $helper,
        Registry $registry, AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->helper = $helper;
    }

    public function getLogoSrc()
    {
        return $this->helper->getLogoSrc();
    }

    /**
     * @return string
     */
    public function getLogoAlt()
    {
        return $this->helper->getLogoAlt();
    }

    /**
     * @return int
     */
    public function getLogoHeight()
    {
        return $this->helper->getLogoHeight();
    }

    /**
     * @return int
     */
    public function getLogoWidth()
    {
        return $this->helper->getLogoWidth();
    }
}
