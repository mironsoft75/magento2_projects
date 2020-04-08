<?php
/**
 * Created by salman.
 * Date: 5/9/18
 * Time: 7:29 PM
 * Filename: Test.php
 */

namespace Pimcore\ImportExport\Console;

use Magento\Catalog\Model\Product\AttributeSet\Options;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Pimcore\Aces\Helper\Data as AcesHelper;
use Pimcore\Aces\Helper\Data as YmmImportHelper;
use Pimcore\ImportExport\Helper\Data;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Test
 * @package Pimcore\ImportExport\Console
 */
class Test extends Command
{
    /**
     * @var Data
     */
    private $helper;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var Options
     */
    private $attributeSets;
    /**
     * @var CollectionFactory
     */
    private $categoryCollection;
    /**
     * @var YmmImportHelper
     */
    private $ymmHelper;
    /**
     * @var AcesHelper
     */
    private $acesHelper;

    /**
     * Test constructor.
     * @param Data                  $helper
     * @param Options               $attributeSets
     * @param CollectionFactory     $categoryCollection
     * @param StoreManagerInterface $storeManager
     * @param YmmImportHelper       $ymmHelper
     */
    public function __construct(
        Data $helper,
        Options $attributeSets,
        CollectionFactory $categoryCollection,
        StoreManagerInterface $storeManager,
        YmmImportHelper $ymmHelper,
        AcesHelper $acesHelper
    )
    {
        parent::__construct();
        $this->helper = $helper;
        $this->storeManager = $storeManager;
        $this->attributeSets = $attributeSets;
        $this->categoryCollection = $categoryCollection;
        $this->ymmHelper = $ymmHelper;
        $this->acesHelper = $acesHelper;
    }

    // Magento 2 API Doc  - https://devdocs.magento.com/swagger/
    protected function execute(InputInterface $input, OutputInterface $output)
    {


        $helper = $this->helper;
        $ymmHelper = $this->ymmHelper;
        $acesHelper = $this->acesHelper;

        $file = $acesHelper->readCsvFile('ProductCategoryAttributes.csv');
        $cat = $file[0];
        $class = $file[1];
        $partId = $file[2];
        $labels = $file[3];
        $attributeSets = array_map(array($this, 'implodeCatsClassPartIds'), $cat, $class, $partId);
        print_r($attributeSets);die;
        unset($file[0]);
        unset($file[1]);
        unset($file[2]);
        unset($file[3]);
        $attributes = array_values($file);
        foreach($attributes as $attributeData){
            $attributeCode = '';
            for($i=0;$i<=(count($attributeData)-1);$i++){
                if($i==0){
                    $attributeCode = $attributeData[$i];
                    echo $attributeCode . "\n";
                    echo $this->camelCaseToUnderscore($attributeCode) . "\n";
                }else{
                    echo $attributeData[$i] == 'Y' ? $attributeSets[$i] . "\n" : '';
                }

            }
            die;
        }
        return $this;
    }

    /**
     * @param $input
     * @return string
     */
    protected function camelCaseToUnderscore($input)
    {
        $input = str_replace(' ','',$input);
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }


    protected function implodeCatsClassPartIds($v1, $v2, $v3)
    {
        return $data = $v1 . '|' . $v2 . '|' . $v3;
    }


    protected function configure()
    {
        $this->setName('pimcore:test')
            ->setDescription('Pimcore Test');
        parent::configure();
    }
}