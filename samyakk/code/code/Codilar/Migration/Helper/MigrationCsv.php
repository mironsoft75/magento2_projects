<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Migration\Helper;

use Magento\Framework\File\Csv;
use Magento\Framework\App\Filesystem\DirectoryList;

class MigrationCsv
{
    /**
     * @var DirectoryList
     */
    private $directoryList;
    /**
     * @var Csv
     */
    private $csvProcessor;

    /**
     * Customer constructor.
     * @param DirectoryList $directoryList
     * @param Csv $csvProcessor
     */
    public function __construct(
        DirectoryList $directoryList,
        Csv $csvProcessor
    ) {
        $this->directoryList = $directoryList;
        $this->csvProcessor = $csvProcessor;
    }

    /**
     * @param array $data
     * @param string $fileName
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function generateCsv($data, $fileName = 'migration.csv') {
        $filePath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR)
            . "/" . $fileName;

        $this->csvProcessor
            ->setDelimiter(',')
            ->setEnclosure('"')
            ->saveData(
                $filePath,
                $data
            );
        return $fileName;
    }
}