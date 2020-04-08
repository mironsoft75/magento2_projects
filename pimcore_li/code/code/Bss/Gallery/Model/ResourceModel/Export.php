<?php
namespace Bss\Gallery\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;

class Export
{
    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $readAdapter;

    /**
     * @var array
     */
    protected $tableNames = [];

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezone;

    /**
     * Export constructor.
     * @param ResourceConnection $resourceConnection
     * @param \Magento\Store\Api\StoreRepositoryInterface $storeRepository
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param array $data
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        array $data = []
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->timezone = $timezone;
        $this->readAdapter = $this->resourceConnection->getConnection('core_read');
    }

    /**
     * @return \Zend_Db_Statement_Interface
     */
    public function getAlbumTable()
    {
        $select = $this->readAdapter->select()
            ->from(
                ['main_table' => $this->getTableName('bss_gallery_category')],
                [
                    '*'
                ]
            )->order(['main_table.category_id']);
        $review = $this->readAdapter->query($select);
        return $review;
    }

    /**
     * @return \Zend_Db_Statement_Interface
     */
    public function getItemTable()
    {
        $select = $this->readAdapter->select()
            ->from(
                ['main_table' => $this->getTableName('bss_gallery_item')],
                [
                    '*'
                ]
            )->order(['main_table.item_id']);
        $review = $this->readAdapter->query($select);
        return $review;
    }

    /**
     * @param int $entity
     * @return bool|string
     */
    protected function getTableName($entity)
    {
        if (!isset($this->tableNames[$entity])) {
            try {
                $this->tableNames[$entity] = $this->resourceConnection->getTableName($entity);
            } catch (\Exception $e) {
                return false;
            }
        }
        return $this->tableNames[$entity];
    }

    /**
     * @param $dateTime
     * @return string
     */
    public function formatDate($dateTime)
    {
        $dateTimeAsTimeZone = $this->timezone
            ->date($dateTime)
            ->format('YmdHis');
        return $dateTimeAsTimeZone;
    }

    /**
     * @param object $albums
     * @return array
     */
    public function getExportAlbums($albums)
    {
        $data[0] = ['Album Id', 'Album Title', 'Album Description', 'Meta Key', 'Meta Description',
            'Layout', 'Auto Play', 'Status', 'Item Ids'];
        foreach ($albums as $album) {
            $row = [];

            $row[] = $album['category_id'];
            $row[] = $album['title'];
            $row[] = $album['category_description'];
            $row[] = $album['category_meta_keywords'];
            $row[] = $album['category_meta_description'];
            $row[] = $album['item_layout'];
            $row[] = $album['slider_auto_play'];
            $row[] = $album['is_active'];
            $row[] = $album['Item_ids'];

            $data[]= $row ;
        }
        return $data;
    }

    public function getExportItems($items)
    {
        $data[0] = ['Item Id', 'Item Name', 'Item Description', 'Image Path', 'Video Url',
            'Sort Order', 'Status', 'Category Ids'];

        foreach ($items as $item) {
            $row = [];

            $row[] = $item['item_id'];
            $row[] = $item['title'];
            $row[] = $item['description'];
            $row[] = $item['image'];
            $row[] = $item['video'];
            $row[] = $item['sorting'];
            $row[] = $item['is_active'];
            $row[] = $item['category_ids'];

            $data[]= $row ;
        }
        return $data;
    }
}
