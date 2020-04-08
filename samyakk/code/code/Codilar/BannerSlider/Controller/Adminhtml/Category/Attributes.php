<?php
/**
 * Created by PhpStorm.
 * User: codilar
 * Date: 17/10/17
 * Time: 2:48 PM
 */

namespace Codilar\BannerSlider\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Codilar\BannerSlider\Helper\Category;
use Magento\Framework\Controller\Result\JsonFactory;

class Attributes extends \Magento\Backend\App\Action
{
    protected $category;
    protected $jsonFactory;

    public function __construct(
        Action\Context $context,
        Category $category,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->category = $category;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $catId = $this->getRequest()->getParam('cat_id');
        $attIds = $this->category->getCategoryFilterableAttrs($catId);
        $attributes = $this->category->attributesData($attIds);
        /*add offer atrribute*/
        $attributes['discount'] = ['options' => [], 'attr_code' => 'discount', 'attr_id' => 'discount'];
        //print_r($attributes);die;
        $resultJson = $this->jsonFactory->create();
        return $resultJson->setData($attributes);
        //die;
    }
}