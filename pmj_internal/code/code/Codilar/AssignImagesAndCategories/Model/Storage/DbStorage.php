<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22/10/19
 * Time: 5:25 PM
 */

namespace Codilar\AssignImagesAndCategories\Model\Storage;

use Magento\Framework\App\ResourceConnection;

/**
 * Class DbStorage
 *
 * @package Codilar\AssignImagesAndCategories\Model\Storage
 */
class DbStorage
{
    /**
     * DB Storage table name
     */
    const TABLE_NAME = 'codilar_image_and_category';

    /**
     * Code of "Integrity constraint violation: 1062 Duplicate entry" error
     */
    const ERROR_CODE_DUPLICATE_ENTRY = 23000;

    /**
     * Connection
     *
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $connection;

    /**
     * Resource
     *
     * @var Resource
     */
    protected $resource;

    /**
     * DbStorage constructor.
     *
     * @param ResourceConnection $resource Resource
     */
    public function __construct(
        ResourceConnection $resource
    ) {
        $this->connection = $resource->getConnection();
        $this->resource = $resource;
    }

    /**
     * InsertMultiple
     *
     * @param array $data Data
     *
     * @return int
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function insertMultiple($data)
    {
        try {
            $tableName = $this->resource->getTableName(self::TABLE_NAME);
            return $this->connection->insertMultiple($tableName, $data);
        } catch (\Exception $e) {
            if ($e->getCode() === self::ERROR_CODE_DUPLICATE_ENTRY
                && preg_match('#SQLSTATE\[23000\]: [^:]+: 1062[^\d]#', $e->getMessage())
            ) {
                throw new \Magento\Framework\Exception\AlreadyExistsException(
                    __('URL key for specified store already exists.')
                );
            }
            throw $e;
        }
    }
}