<?php
namespace Bss\Gallery\Block\Adminhtml\Item\Grid;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;
use Magento\Store\Model\StoreManagerInterface;

class ImageRenderer extends AbstractRenderer
{
    private $_storeManager;

    /**
     * @param \Magento\Backend\Block\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        StoreManagerInterface $storemanager,
        array $data = []
    )
    {
        $this->_storeManager = $storemanager;
        parent::__construct($context, $data);
        $this->_authorization = $context->getAuthorization();
    }

    /**
     * Renders grid column
     *
     * @param Object $row
     * @return  string
     */
    public function render(DataObject $row)
    {
        $subDir = 'Bss/Gallery/Item/image';
        $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );
        $imageUrl = $mediaDirectory . $subDir . $this->_getValue($row);
        $imageUrl = str_replace('https://', 'http://', $imageUrl);
        if (@getimagesize($imageUrl)) {
            return '<img src="' . $imageUrl . '" width="75"/>';
        }
        return '<img src="' . $this->getViewFileUrl('Bss_Gallery::images/default-image.jpg') . '" width="75"/>';
    }
}
