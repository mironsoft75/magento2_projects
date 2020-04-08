<?php
namespace WeSupply\Toolbox\Model;

class ClientNameComment implements \Magento\Config\Model\Config\CommentInterface
{
    /**
     * @var \WeSupply\Toolbox\Helper\Data
     */
    public $helper;

    /**
     * Comment constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \WeSupply\Toolbox\Helper\Data $helper
    )
    {
        $this->helper = $helper;
    }

    /**
     * @param string $elementValue
     * @return string
     */
    public function getCommentText($elementValue)
    {
        $clientName = $this->helper->getClientName();
        if($clientName){
            return 'Your Client Name is:  <strong>'.$clientName.'</strong>';
        }else{
            return 'Please complete <strong>WeSupply SubDomain</strong> field from <strong>Step 1 - Define your WeSupply SubDomain</strong> Configuration tab, to receive your Client Name!';
        }

    }

}