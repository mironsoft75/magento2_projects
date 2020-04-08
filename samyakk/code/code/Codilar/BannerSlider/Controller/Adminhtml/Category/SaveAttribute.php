<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\BannerSlider\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magestore\Bannerslider\Model\Banner;
use Magestore\Bannerslider\Model\ResourceModel\Banner as ResourceBanner;

class SaveAttribute extends Action
{

    /**
     * @var Banner
     */
    protected $banner;

    /**
     * @var ResourceBanner
     */
    protected $resourceBanner;

    /**
     * SaveAttribute constructor.
     * @param Action\Context $context
     * @param Banner $banner
     * @param ResourceBanner $resourceBanner
     */
    public function __construct(
        Action\Context $context,
        Banner $banner,
        ResourceBanner $resourceBanner
    ) {
        parent::__construct($context);
        $this->resourceBanner = $resourceBanner;
        $this->banner = $banner;
    }


    /**
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $isRemove = $this->getRequest()->getParam('remove');
        $banner = $this->banner;
        $this->resourceBanner->load($banner, $data['banner']);
        $attrs = json_decode($banner->getData('attribute_options'), true);
        if (!$attrs) {
            $attrs = [];
        }
        if (!$isRemove) {
            if (array_key_exists($data['attr'], $attrs)) {
                array_push($attrs[$data['attr']], $data['option']);
            } else {
                $attrs[$data['attr']] = [$data['option']];
            }
        } else {
            if( array_key_exists($data['attr'], $attrs) && ($key = array_search($data['option'], $attrs[$data['attr']])) !== false) {
                unset($attrs[$data['attr']][$key]);
                if (!$attrs[$data['attr']]){
                    unset($attrs[$data['attr']]);
                }
            }
        }
        $banner->setData('attribute_options', json_encode($attrs));
        $this->resourceBanner->save($banner);
    }
}
