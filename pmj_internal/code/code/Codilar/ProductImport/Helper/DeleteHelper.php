<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30/8/19
 * Time: 10:23 AM
 */

namespace Codilar\ProductImport\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Psr\Log\LoggerInterface;
use Magento\Framework\Model\ResourceModel\Db\TransactionManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\ObjectRelationProcessor;
use Magento\CatalogImportExport\Model\Import\Proxy\Product\ResourceModelFactory;
use Magento\Framework\App\ResourceConnection;

/**
 * Class DeleteHelper
 * @package Codilar\ProductImport\Helper
 */
class DeleteHelper extends AbstractHelper
{
    /**
     * Logger Interface
     *
     * @var LoggerInterface
     */
    protected $_logger;
    /**
     * @var TransactionManagerInterface
     */
    protected $transactionManager;
    /**
     * @var ObjectRelationProcessor
     */
    protected $objectRelationProcessor;
    /**
     * @var ResourceModelFactory
     */
    private $_resourceFactory;
    /**
     * DB connection
     *
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    private $_connection;
    /**
     * @var ResourceConnection
     */
    protected $resource;
    protected $__connection;

    /**
     * DeleteHelper constructor.
     *
     * @param TransactionManagerInterface $transactionManager
     * @param ObjectRelationProcessor $objectRelationProcessor
     * @param ResourceModelFactory $resourceFactory
     * @param ResourceConnection $resource
     * @param LoggerInterface $logger
     * @param Context $context
     */
    public function __construct(
        TransactionManagerInterface $transactionManager,
        ObjectRelationProcessor $objectRelationProcessor,
        ResourceModelFactory $resourceFactory,
        ResourceConnection $resource,
        LoggerInterface $logger,
        Context $context
    )
    {
        $this->transactionManager = $transactionManager;
        $this->objectRelationProcessor = $objectRelationProcessor;
        $this->_resourceFactory = $resourceFactory;
        $this->resource = $resource;
        $this->_connection = $resource->getConnection();
        $this->_logger = $logger;
        parent::__construct($context);
    }

    /**
     * DB connection getter.
     *
     * @return \Magento\Framework\DB\Adapter\AdapterInterface
     */
    public function getConnection()
    {
        return $this->_connection;
    }

    /**
     * Product Mass Delete
     *
     * @param $idsToDelete
     * @return $this
     */
    public function productMassDelete($idsToDelete)
    {
        try {
            /**
             * Resource Model
             *
             * @var $productEntityTable \Magento\CatalogImportExport\Model\Import\Proxy\Product\ResourceModel
             */
            $productEntityTable = $this->_resourceFactory->create()
                ->getEntityTable();
            if ($idsToDelete) {
                $this->transactionManager->start($this->_connection);
                try {
                    $this->objectRelationProcessor->delete(
                        $this->transactionManager,
                        $this->_connection,
                        $productEntityTable,
                        $this->_connection
                            ->quoteInto('sku IN (?)', $idsToDelete),
                        ['sku' => $idsToDelete]
                    );
                    $this->transactionManager->commit();
                } catch (\Exception $e) {
                    $this->transactionManager->rollBack();
                    throw $e;
                }
            }
            return $this;

        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }
}