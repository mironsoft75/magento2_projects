<?php
/**
 * Created by salman.
 * Date: 5/9/18
 * Time: 7:29 PM
 * Filename: Test.php
 */

namespace Pimcore\ImportExport\Console;


use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Test
 * @package Pimcore\ImportExport\Console
 */
class UniqueOptions extends Command
{


    /**
     * @var State
     */
    private $state;
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * FirstImagePlaceholder constructor.
     */
    public function __construct(
        State $state,
        ResourceConnection $resourceConnection

    )
    {
        parent::__construct();

        $this->state = $state;
        $this->resourceConnection = $resourceConnection;
    }

    // Magento 2 API Doc  - https://devdocs.magento.com/swagger/
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML); // or \Magento\Framework\App\Area::AREA_ADMINHTML, depending on your needs
        $resourceConnection = $this->resourceConnection->getConnection();
        $query = "SELECT `value_id`, `value`, `attribute_id`, `row_id` FROM catalog_product_entity_varchar";
        $allAttrs = $resourceConnection->fetchAll($query);
        foreach ($allAttrs as $allAttr) {
            if ($allAttr['value']) {
                $uniqueAttributes = implode(",", array_unique(explode(',', $allAttr['value'])));
                $attributeId = $allAttr['attribute_id'];
                $valueId = $allAttr['value_id'];
                $rowId = $allAttr['row_id'];
                $newQ = "UPDATE `catalog_product_entity_varchar` SET `value` = '" . $this->escape($uniqueAttributes) . "' where `attribute_id` = $attributeId AND `row_id` = $rowId and `value_id` = $valueId";
                $resourceConnection->query($newQ);
            }
        }

        return $this;
    }

    public function escape($aQuery)
    {
        return strtr($aQuery, array("\x00" => '\x00', "\n" => '\n', "\r" => '\r', '\\' => '\\\\', "'" => "\'", '"' => '\"', "\x1a" => '\x1a'));
    }

    protected function configure()
    {
        $this->setName('pimcore:uniqueoptions')
            ->setDescription('Pimcore uniqueoptions');
        parent::configure();
    }
}