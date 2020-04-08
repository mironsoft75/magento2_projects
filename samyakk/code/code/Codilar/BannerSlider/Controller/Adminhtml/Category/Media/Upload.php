<?php
/**
 * Created by PhpStorm.
 * User: codilar
 * Date: 19/10/17
 * Time: 5:37 PM
 */

namespace Codilar\BannerSlider\Controller\Adminhtml\Category\Media;

use Magento\Backend\App\Action;
use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\Controller\ResultFactory;

class Upload extends Action
{

    protected $imageUploader;

    /**
     * Upload constructor.
     * @param Action\Context $context
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        Action\Context $context,
        ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $result = $this->imageUploader->saveFileToTmpDir('mobile_image');

            $result['cookie'] = [
                'name' 	=> $this->_getSession()->getName(),
                'value' 	=> $this->_getSession()->getSessionId(),
                'lifetime'   => $this->_getSession()->getCookieLifetime(),
                'path'       => $this->_getSession()->getCookiePath(),
                'domain'   => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
