<?php
/**
 * Created by salman.
 * Date: 5/9/18
 * Time: 5:59 PM
 * Filename: Data.php
 */

namespace Pimcore\Stores\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\File\Csv;
use Magento\Framework\Module\Dir\Reader;
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
     * Data constructor.
     * @param Context         $context
     * @param LoggerInterface $logger
     * @param Reader          $moduleReader
     * @param Csv             $fileCsv
     */
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        Reader $moduleReader,
        Csv $fileCsv
    )
    {
        parent::__construct($context);
        $this->logger = $logger;
        $this->moduleReader = $moduleReader;
        $this->fileCsv = $fileCsv;
    }

    /**
     * @param $fileName
     * @return string
     */
    public function readCsvFile($fileName)
    {
        $directory = $this->moduleReader->getModuleDir('',$this->_getModuleName());
        $file = $directory . '/Files/' . $fileName;
        $data = '';
        if (file_exists($file)) {
            $data = $this->fileCsv->getData($file);
        }
        return $data;
    }


}