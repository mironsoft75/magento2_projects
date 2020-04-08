<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\BannerSlider\Controller\Adminhtml\Category\Media;

use Magento\Backend\App\Action;
use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\Controller\ResultFactory;

class UploadWebsite extends Action
{

    protected $imageUploader;

    /**
     * UploadWebsite constructor.
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
            $result = $this->imageUploader->saveFileToTmpDir('website_image');

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
