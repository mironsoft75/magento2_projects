<?php
namespace Pimcore\ImportExport\Controller\GetLists;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Pimcore\ImportExport\Helper\Data;

class Categories extends \Magento\Framework\App\Action\Action
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
        $data = $this->helper->getCategoryList();
        $this->getCsvFile("categories-list-for-pimcore.csv",$data,"Category name,Display name,category_id");
        return $this->resultPageFactory->create();
    }

    public function getCsvFile($name,$colValues,$heads)
    {
        $data = $heads . PHP_EOL;
        foreach ($colValues as $value){
            $data .= implode(",",$value) . PHP_EOL;
        }
        $this->fileFactory->create(
            $name,
            $data,
            DirectoryList::VAR_DIR,
            'application/octet-stream'
        );
    }
}
