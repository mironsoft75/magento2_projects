<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\BannerSlider\Controller\Adminhtml\Category;

use Magento\Framework\App\ResponseInterface;
use Codilar\BannerSlider\Helper\Category;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Backend\App\Action;

class AttributeOptions extends Action
{

    /**
     * @var Category
     */
    protected $category;

    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * AttributeOptions constructor.
     * @param Action\Context $context
     * @param Category $category
     * @param JsonFactory $jsonFactory
     */
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
     */
    public function execute()
    {
        $attr_code = $this->getRequest()->getParam('attr_code');
        $options = $this->category->getAttributeOptions($attr_code);
        $resultJson = $this->jsonFactory->create();
        return $resultJson->setData($options);
    }
}
