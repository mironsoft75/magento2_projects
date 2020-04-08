<?php
/**
 *
 * @package     nesto
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\AdminLogs\Console;


use Codilar\AdminLogs\Api\AdminLogsRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem\DirectoryList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Export extends Command
{
    const DESTINATION_FILE_TYPE = "type";
    const DESTINATION_FILE_PATH = "destination";
    const DEFAULT_DESTINATION_FILE_PATH = "/var/log/adminlogs";
    const DEFAULT_DESTINATION_FILE_TYPE = "csv";
    const FLUSH_DB_AFTER_EXPORT = "flush";

    /**
     * @var DirectoryList
     */
    private $directoryList;
    /**
     * @var AdminLogsRepositoryInterface
     */
    private $adminLogsRepository;
    /**
     * @var Csv
     */
    private $csv;

    /**
     * Export constructor.
     * @param DirectoryList $directoryList
     * @param AdminLogsRepositoryInterface $adminLogsRepository
     * @param Csv $csv
     * @param null|string $name
     */
    public function __construct(
        DirectoryList $directoryList,
        AdminLogsRepositoryInterface $adminLogsRepository,
        Csv $csv,
        ?string $name = null
    )
    {
        parent::__construct($name);
        $this->directoryList = $directoryList;
        $this->adminLogsRepository = $adminLogsRepository;
        $this->csv = $csv;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $options = [
            new InputOption(self::DESTINATION_FILE_PATH,'d',InputOption::VALUE_OPTIONAL,__("Destination file path where logs should export")->render()),
            new InputOption(self::DESTINATION_FILE_TYPE,'t',InputOption::VALUE_OPTIONAL,__("Destination file type (%1)", implode("|", $this->getAllowedFileTypes()))->render()),
            new InputOption(self::FLUSH_DB_AFTER_EXPORT,'f',InputOption::VALUE_NONE,__("Flush DB after export")->render())
        ];
        $this->setName('adminlogs:export');
        $this->setDescription(__("Export adminlogs in CSV format to destination file path"));
        $this->setDefinition($options);
        parent::configure();
    }

    protected function getDefaultDestinationPath()
    {
        return $this->directoryList->getRoot() . self::DEFAULT_DESTINATION_FILE_PATH;
    }

    protected function getAllowedFileTypes() {
        return [
            'json',
            'csv'
        ];
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $destination = $input->getOption(self::DESTINATION_FILE_PATH);
        $type = $input->getOption(self::DESTINATION_FILE_TYPE);
        $flush = $input->getOption(self::FLUSH_DB_AFTER_EXPORT);
        if (!$type) {
            $type = self::DEFAULT_DESTINATION_FILE_TYPE;
        }
        if (!in_array($type, $this->getAllowedFileTypes())) {
            throw new LocalizedException(__("Type '%1' is not supported", $type));
        }
        if (!$destination) {
            $destination = $this->getDefaultDestinationPath() . '.' . $type;
        }

        $collection = $this->adminLogsRepository->getCollection();
        $data = $collection->toArray()['items'];
        $totalRecords = count($data);

        $output->writeln(__("<info>Writing %1 records to file</info>", $totalRecords)->render());

        switch ($type) {
            case 'csv':
                $headers = $this->adminLogsRepository->getColumns();
                array_unshift($data, $headers);
                $this->csv->saveData($destination, $data);
                break;
            case 'json':
                $fh = fopen($destination, 'w');
                $data = \json_encode($data);
                fputs($fh, $data, strlen($data));
                fclose($fh);
                break;
        }

        $output->writeln(__("<info>%1 records saved to %2</info>", $totalRecords, $destination)->render());

        if ($flush) {
            foreach ($collection as $item) {
                $this->adminLogsRepository->delete($item);
            }
            $output->writeln(__("<info>%1 records flushed from database</info>", $totalRecords, $destination)->render());
        }
    }
}