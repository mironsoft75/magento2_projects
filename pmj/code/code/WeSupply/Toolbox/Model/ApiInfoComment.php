<?php
namespace WeSupply\Toolbox\Model;

class ApiInfoComment implements \Magento\Config\Model\Config\CommentInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $storeManager;

    /**
     * Comment constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->storeManager = $storeManager;
    }

    /**
     * @param string $elementValue
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCommentText($elementValue)
    {
        $baseUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
        return 'Your API Endpoint is:  <strong>'.$baseUrl.'wesupply</strong>';
    }

}