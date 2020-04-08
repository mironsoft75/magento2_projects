<?php
/**
 * Created by salman.
 * Date: 4/9/18
 * Time: 8:34 PM
 * Filename: Data.php
 */

namespace Pimcore\Common\Helper;


use \Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Data
 * @package Pimcore\Common\Helper
 */
class Data extends AbstractHelper
{

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Data constructor.
     * @param Context               $context
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
    }

    /**
     * @return \Magento\Store\Api\Data\StoreInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStore()
    {
        return $this->storeManager->getStore();
    }
}