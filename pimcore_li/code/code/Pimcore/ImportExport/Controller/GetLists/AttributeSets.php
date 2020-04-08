<?php
namespace Pimcore\ImportExport\Controller\GetLists;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Pimcore\ImportExport\Helper\Data;

class AttributeSets extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;
    /**
     * @var Data
     */
    private $helper;
    /**
     * @var FileFactory
     */
    private $fileFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        Data $helper,
        FileFactory $fileFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        $this->helper = $helper;
        $this->fileFactory = $fileFactory;
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->helper->getAttributeSetsList();
        $this->getCsvFile("attribute_set-list-for-pimcore.csv",$data,"attribute set name,attribute_set_id");
        return $this->resultPageFactory->create();
    }

    /**
     * @param $name
     * @param $colValues
     * @param $heads
     */
    public function getCsvFile($name,$colValues,$heads)
    {
        $data = $heads . PHP_EOL;
        foreach ($colValues as $key => $value){
            $data .= "$key,$value" . PHP_EOL;
        }
        $this->fileFactory->create(
            $name,
            $data,
            DirectoryList::VAR_DIR,
            'application/octet-stream'
        );
    }
}
