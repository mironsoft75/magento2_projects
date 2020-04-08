<?php
/**
 * Created by salman.
 * Date: 5/9/18
 * Time: 5:59 PM
 * Filename: Data.php
 */

namespace Pimcore\Aces\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\File\Csv;
use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\Registry;
use Pimcore\Aces\Api\AcesProductsManagementInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Data
 * @package Pimcore\ImportExport\Helper
 */
class Data extends AbstractHelper
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Reader
     */
    private $moduleReader;
    /**
     * @var Csv
     */
    private $fileCsv;
    /**
     * @var AcesProductsManagementInterface
     */
    private $acesProductsManagement;
    /**
     * @var Registry
     */
    private $registry;

    /**
     * Data constructor.
     * @param Context                         $context
     * @param LoggerInterface                 $logger
     * @param Reader                          $moduleReader
     * @param Csv                             $fileCsv
     * @param AcesProductsManagementInterface $acesProductsManagement
     */
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        Reader $moduleReader,
        Csv $fileCsv,
        AcesProductsManagementInterface $acesProductsManagement,
        Registry $registry

    )
    {
        parent::__construct($context);
        $this->logger = $logger;
        $this->moduleReader = $moduleReader;
        $this->fileCsv = $fileCsv;
        $this->acesProductsManagement = $acesProductsManagement;
        $this->registry = $registry;
    }

    /**
     * @param $fileName
     * @return string
     */
    public function readCsvFile($fileName)
    {
        $directory = $this->moduleReader->getModuleDir('', $this->_getModuleName());
        $file = $directory . '/Files/' . $fileName;
        $data = '';
        if (file_exists($file)) {
            $data = $this->fileCsv->getData($file);
        }
        return $data;
    }

    /**
     * @return mixed
     */
    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }

    public function getAcesVehicleList($sku,$selectAttributes)
    {
        return $this->acesProductsManagement->getAcesVehicleList($sku,$selectAttributes);
    }


}