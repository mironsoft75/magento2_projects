<?php
/** 
 * BSS Commerce Co. 
 * 
 * NOTICE OF LICENSE 
 * 
 * This source file is subject to the EULA 
 * that is bundled with this package in the file LICENSE.txt. 
 * It is also available through the world-wide-web at this URL: 
 * http://bsscommerce.com/Bss-Commerce-License.txt 
 * 
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE 
 * ================================================================= 
 * This package designed for Magento COMMUNITY edition 
 * BSS Commerce does not guarantee correct work of this extension 
 * on any other Magento edition except Magento COMMUNITY edition. 
 * BSS Commerce does not provide extension support in case of 
 * incorrect edition usage. 
 * ================================================================= 
 * 
 * @category   BSS 
 * @package    Bss_Gallery 
 * @author     Extension Team 
 * @copyright  Copyright (c) 2015-2016 BSS Commerce Co. ( http://bsscommerce.com ) 
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt 
 */ 
namespace Bss\Gallery\Block\Adminhtml\Category\Edit\Tab;

/**
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Image extends \Magento\Backend\Block\Template
{
    /**
     * User model factory
     *
     * @var \Magento\User\Model\Resource\User\CollectionFactory
     */
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'category/tab_image_js.phtml';

    protected $blockGrid;

    protected $registry;

    protected $jsonEncoder;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Backend\Model\Auth\Session $authSession
     * @param \Magento\User\Model\Resource\User\CollectionFactory $userCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        array $data = []
    )
    {
        $this->registry = $registry;
        $this->jsonEncoder = $jsonEncoder;
        parent::__construct($context, $data);
    }

    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $id = $this->getRequest()->getParam('category_id', false);
    }


    public function getBlockGrid()
    {
        if (null === $this->blockGrid) {
            $this->blockGrid = $this->getLayout()->createBlock(
                'Bss\Gallery\Block\Adminhtml\Category\Edit\Tab\ListImage'
            );
        }
        return $this->blockGrid;
    }

    /**
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getBlockGrid()->toHtml();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/listimage', array('_current' => true));
    }

    public function getItemThumbId()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $session = $objectManager->create('Magento\Catalog\Model\Session');
        // $session->unsCategoryThumb();
        $thumb = $session->getCategoryThumb();
        $keys = $session->getKeySession();
        $category = $this->registry->registry('gallery_category');
        if ($thumb && $keys && $thumb['keys'] == $keys) {
            $id = $thumb['id'];
        } else {
            $id = $category->getItemThumbId();
        }

        return $id;
    }

    public function getKeySession()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $session = $objectManager->create('Magento\Catalog\Model\Session');
        $keys = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 20);
        $session->setKeySession($keys);
        return $keys;
    }

}
