<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23/10/19
 * Time: 4:32 PM
 */

namespace Codilar\AssignImagesAndCategories\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Codilar\AssignImagesAndCategories\Helper\Data;
use Magento\Framework\App\State;
use Psr\Log\LoggerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Escaper;

/**
 * Class AddImagesAndCategories
 *
 * @package Codilar\AssignImagesAndCategories\Console
 */
class AddImagesAndCategories extends Command
{
    /**
     * Logger Interface
     *
     * @var LoggerInterface
     */
    protected $_logger;
    /**
     * Image Helper
     *
     * @var Data
     */
    protected $imageHelper;
    /**
     * State
     *
     * @var State
     */
    private $_state;
    /**
     * TransportBuilder
     *
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $transportBuilder;

    /**
     * StateInterface
     *
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * ScopeConfigInterface
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * StoreManagerInterface
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * Escaper
     *
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;


    /**
     * AddImagesAndCategories constructor.
     *
     * @param Data $data
     * @param State $state
     * @param LoggerInterface $logger
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param ScopeConfigInterface $scopeConfig
     * @param Escaper $escaper
     */
    public function __construct(
        Data $data,
        State $state,
        LoggerInterface $logger,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        ScopeConfigInterface $scopeConfig,
        Escaper $escaper
    )
    {
        $this->_logger = $logger;
        $this->imageHelper = $data;
        $this->_state = $state;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->escaper = $escaper;
        parent::__construct();
    }

    /**
     * configure
     */
    protected function configure()
    {
        $this->setName('codilar:addimagesandcategories')
            ->setDescription('Codilar AddImagesAndCategories Products');
        parent::configure();
    }


    /**
     * Execute
     *
     * @param InputInterface  $input  Input
     * @param OutputInterface $output OutPut
     *
     * @return bool|int|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {

        try {
            $this->_state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
            $this->imageHelper->insertData();
            return true;
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }
    }
}