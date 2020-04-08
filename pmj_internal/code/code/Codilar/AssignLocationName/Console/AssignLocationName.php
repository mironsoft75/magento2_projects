<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 28/11/19
 * Time: 3:23 PM
 */

namespace Codilar\AssignLocationName\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Codilar\AssignLocationName\Helper\Data;
use Magento\Framework\App\State;
use Psr\Log\LoggerInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Escaper;
use Codilar\ComputePrice\Helper\PriceHelper;
use Magento\Framework\Event\Manager;

class AssignLocationName extends Command
{
    const EMAIL = 'email';
    /**
     * Logger Interface
     *
     * @var LoggerInterface
     */
    protected $_logger;
    /**
     * Location Helper
     *
     * @var Data
     */
    protected $locationHelper;
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
     * Price Helper
     *
     * @var PriceHelper
     */
    protected $mailHelper;
    /**
     * Event Manager
     *
     * @var Manager
     */
    protected $eventManager;

    /**
     * AssignImagesAndCategories constructor.
     *
     * @param Data $data
     * @param State $state
     * @param LoggerInterface $logger
     * @param TransportBuilder $transportBuilder
     * @param StateInterface $inlineTranslation
     * @param ScopeConfigInterface $scopeConfig
     * @param PriceHelper $priceHelper
     * @param Manager $eventManager
     * @param Escaper $escaper
     */
    public function __construct(
        Data $data,
        State $state,
        LoggerInterface $logger,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        ScopeConfigInterface $scopeConfig,
        priceHelper $priceHelper,
        Manager $eventManager,
        Escaper $escaper
    )
    {
        $this->_logger = $logger;
        $this->locationHelper = $data;
        $this->_state = $state;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->escaper = $escaper;
        $this->mailHelper = $priceHelper;
        $this->eventManager = $eventManager;
        parent::__construct();
    }

    /**
     * configure
     */
    protected function configure()
    {
        $this->setName('codilar:assignlocationname')
            ->setDescription('Codilar AssiginLocationName To Products');
        parent::configure();
    }

    /**
     * Send Mail
     */
    public function sendMail()
    {
        $this->inlineTranslation->suspend();
        try {
            $post = [
                'name' => $this->mailHelper->senderName(),
                self::EMAIL => $this->mailHelper->senderEmail(),
            ];
            $sender = [
                'name' => $this->escaper->escapeHtml($post['name']),
                self::EMAIL => $this->escaper->escapeHtml($post[self::EMAIL]),
            ];
            $transport = $this->transportBuilder
                ->setTemplateIdentifier('location_name')
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_ADMINHTML,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars(['data' => $post])
                ->setFrom($sender)
                ->addTo($this->mailHelper->receiverEmail())
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->inlineTranslation->resume();
            $this->_logger->critical($e);
        }
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
            $entityData="done";
            $this->_state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
            $this->locationHelper->updateLocationName();
            if ($this->mailHelper->sendMail()) {
                $this->sendMail();
            }
            $this->eventManager->dispatch(
                'codilar_product_price_compute_after',
                ['newData' => $entityData]
            );
            return true;
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }
    }
}