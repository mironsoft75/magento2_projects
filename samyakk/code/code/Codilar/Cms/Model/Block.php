<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Cms\Model;

use Codilar\Cms\Api\Data\BlockInterface;
use Magento\Cms\Model\Block as Subject;


class Block extends Subject implements BlockInterface
{
    /**
     * @var \Codilar\Cms\Helper\Filter
     */
    private $filterHelper;


    /**
     * Block constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Codilar\Cms\Helper\Filter $filterHelper
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Codilar\Cms\Helper\Filter $filterHelper,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->filterHelper = $filterHelper;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->getData("block_id");
    }

    /**
     * @return int
     */
    public function getSortOrder()
    {
        return (int)$this->getData("sort_order");
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getContent()
    {
        $content = $this->getData('content');
        return $this->filterHelper->filterStaticContent($content);
    }


    /**
     * @param int $sortOrder
     * @return \Codilar\Cms\Api\Data\BlockInterface
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData("sort_order", $sortOrder);
    }

    /**
     * @return string
     */
    public function getDesignIdentifier()
    {
        return $this->getData('design_identifier');
    }

    /**
     * @param string $designIdentifier
     * @return $this
     */
    public function setDesignIdentifier($designIdentifier)
    {
        return $this->setData('design_identifier', $designIdentifier);
    }
}
