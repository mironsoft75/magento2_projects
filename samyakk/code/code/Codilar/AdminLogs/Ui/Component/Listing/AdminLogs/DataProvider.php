<?php
/**
 *
 * @package     magento2
 * @author      Codilar
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        https://www.codilar.com/
 */

namespace Codilar\AdminLogs\Ui\Component\Listing\AdminLogs;


use Codilar\AdminLogs\Api\AdminLogsRepositoryInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var AdminLogsRepositoryInterface
     */
    private $adminLogsRepository;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param AdminLogsRepositoryInterface $adminLogsRepository
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        AdminLogsRepositoryInterface $adminLogsRepository,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $adminLogsRepository->getCollection();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->adminLogsRepository = $adminLogsRepository;
    }

    /**
     * @param \Magento\Framework\Api\Filter $filter
     * @return void
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        if ($filter->getField() === 'fulltext') {
            $columns = $this->adminLogsRepository->getColumns();
            $conditions = array_fill(0, count($columns), $conditions[] = ['like' => '%' . $filter->getValue() . '%']);
            $this->getCollection()->addFieldToFilter($columns, $conditions);
        } else {
            parent::addFilter($filter);
        }
    }
}