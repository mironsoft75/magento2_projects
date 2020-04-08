<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Store\Model;

use Codilar\Api\Api\AbstractApi;
use Codilar\Api\Helper\Cookie;
use Codilar\Store\Api\CurrencySwitcherRepositoryInterface;
use Codilar\Store\Api\Data\CurrencyInterface;
use Codilar\Store\Api\Data\CurrencyInterfaceFactory;
use Codilar\Store\Api\Data\CurrencyDataInterface;
use Codilar\Store\Api\Data\CurrencyDataInterfaceFactory;
use Codilar\Store\Api\StoreRepositoryInterface;
use Magento\CurrencySymbol\Model\System\Currencysymbol;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Webapi\Rest\Response;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\ResourceConnection;
use Codilar\Api\Helper\Customer as CustomerApiHelper;
use Codilar\Api\Api\Data\Repositories\AbstractResponseDataInterface;
use Codilar\Api\Api\Data\Repositories\AbstractResponseDataInterfaceFactory;

class StoreRepository extends AbstractApi implements StoreRepositoryInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Magento\Directory\Model\CurrencyFactory
     */
    private $currencyFactory;
    /**
     * @var CurrencyInterfaceFactory
     */
    private $currencyDataFactory;
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;
    /**
     * @var Currencysymbol
     */
    private $currencysymbol;
    /**
     * @var CustomerApiHelper
     */
    private $customerApiHelper;
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;
    /**
     * @var AbstractResponseDataInterfaceFactory
     */
    private $abstractResponseDataFactory;
    /**
     * @var CurrencySwitcherRepositoryInterface
     */
    private $currencySwitcherRepository;

    /**
     * StoreRepository constructor.
     * @param Cookie $cookieHelper
     * @param RequestInterface $request
     * @param Response $response
     * @param Session $customerSession
     * @param DataObjectFactory $dataObjectFactory
     * @param StoreManagerInterface $storeManager
     * @param CurrencyInterfaceFactory $currencyFactory
     * @param ResourceConnection $resourceConnection
     * @param Currencysymbol $currencysymbol
     * @param CurrencyDataInterfaceFactory $currencyDataFactory
     * @param CustomerApiHelper $customerApiHelper
     * @param CartRepositoryInterface $cartRepository
     * @param AbstractResponseDataInterfaceFactory $abstractResponseDataFactory
     * @param CurrencySwitcherRepositoryInterface $currencySwitcherRepository
     */
    public function __construct(
        Cookie $cookieHelper,
        RequestInterface $request,
        Response $response,
        Session $customerSession,
        DataObjectFactory $dataObjectFactory,
        StoreManagerInterface $storeManager,
        CurrencyInterfaceFactory $currencyFactory,
        ResourceConnection $resourceConnection,
        Currencysymbol $currencysymbol,
        CurrencyDataInterfaceFactory $currencyDataFactory,
        CustomerApiHelper $customerApiHelper,
        CartRepositoryInterface $cartRepository,
        AbstractResponseDataInterfaceFactory $abstractResponseDataFactory,
        CurrencySwitcherRepositoryInterface $currencySwitcherRepository
    )
    {
        parent::__construct($cookieHelper, $request, $response, $customerSession, $dataObjectFactory);
        $this->storeManager = $storeManager;
        $this->currencyFactory = $currencyFactory;
        $this->currencyDataFactory = $currencyDataFactory;
        $this->resourceConnection = $resourceConnection;
        $this->currencysymbol = $currencysymbol;
        $this->customerApiHelper = $customerApiHelper;
        $this->cartRepository = $cartRepository;
        $this->abstractResponseDataFactory = $abstractResponseDataFactory;
        $this->currencySwitcherRepository = $currencySwitcherRepository;
    }

    /**
     * @return \Codilar\Store\Api\Data\StoreInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getStores()
    {
        /** @var \Magento\Store\Model\Website $website */
        $website = $this->storeManager->getWebsite();
        $stores = $website->getStores();
        /** @var \Codilar\Store\Api\Data\StoreInterface $store */
        return $this->sendResponse($stores);
    }

    /**
     * @return \Codilar\Store\Api\Data\CurrencyDataInterface
     * @throws NoSuchEntityException
     */
    public function getCurrencies()
    {
        /** @var \Magento\Store\Model\Store $store */
        $store = $this->storeManager->getStore();
        $connection = $this->resourceConnection->getConnection();
        $currentCurrency = $this->getCurrentCurrency($store);
        $query = $connection->select()->from($connection->getTableName('directory_currency_rate'), [])
            ->columns([
                'currency_code' =>  'currency_to',
                'rate'  =>  'rate'
            ])->where($connection->quoteInto('currency_to IN (?)', $store->getAllowedCurrencies()))
            ->where($connection->quoteInto('currency_from = ?', $store->getBaseCurrencyCode()))
            ->where($connection->quoteInto('currency_to != ?', $currentCurrency->getCurrencyCode()));
        $currencies = [];
        $currencySymbolData = $this->currencysymbol->getCurrencySymbolsData();
        foreach ($query->query()->fetchAll() as $currency) {
            /** @var CurrencyInterface $currencyData */
            $currencyData = $this->currencyFactory->create();
            $currencyData->setCurrencyCode($currency['currency_code'])
                ->setRate($currency['rate'])
                ->setCurrencySign(
                    array_key_exists($currency['currency_code'], $currencySymbolData)
                        ? $currencySymbolData[$currency['currency_code']]['displaySymbol'] :
                        $currency['currency_code']
                );
            $currencies[] = $currencyData;
        }

        $currencies = array_merge([$currentCurrency], $currencies);


        /** @var CurrencyDataInterface $currentCurrencyData */
        $currentCurrencyData = $this->currencyDataFactory->create();
        $currentCurrencyData->setCurrencies($currencies)->setCurrentCurrency($currentCurrency);
        return $this->sendResponse($currentCurrencyData);
    }

    /**
     * @param \Magento\Store\Model\Store $store
     * @return CurrencyInterface
     */
    protected function getCurrentCurrency($store)
    {
        /** @var CurrencyInterface $currencyData */
        $currencyData = $this->currencyFactory->create();
        $baseCurrency = $store->getDefaultCurrency();
        $currencyData->setCurrencyCode($baseCurrency->getCurrencyCode())
            ->setCurrencySign($baseCurrency->getCurrencySymbol());
        $currencyData->setRate($baseCurrency->getRate($currencyData->getCurrencyCode()));
        return $this->sendResponse($currencyData);
    }

    /**
     * @param string $currencyCode
     * @return \Codilar\Api\Api\Data\Repositories\AbstractResponseDataInterface
     * @throws NoSuchEntityException
     * @throws AlreadyExistsException
     */
    public function setQuoteCurrency($currencyCode)
    {
        $allowedCurrencies = $this->getCurrencies()->getCurrencies();
        $illegalCurrencyCode = true;
        foreach ($allowedCurrencies as $allowedCurrency) {
            if ($allowedCurrency->getCurrencyCode() === $currencyCode) {
                $illegalCurrencyCode = false;
                break;
            }
        }
        if ($illegalCurrencyCode) {
            throw NoSuchEntityException::singleField('currency_code', $currencyCode);
        }

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->customerApiHelper->getCustomerQuoteByToken();
        try {
            $currencyModel = $this->currencySwitcherRepository->load($quote->getId(), 'quote_id');
        } catch (NoSuchEntityException $e) {
            $currencyModel = $this->currencySwitcherRepository->create();
        }
        $currencyModel->setQuoteId($quote->getId())
            ->setQuoteCurrency($quote->getQuoteCurrencyCode())
            ->setUpdateCurrencyTo($currencyCode);
        $this->currencySwitcherRepository->save($currencyModel);

        /** @var AbstractResponseDataInterface $response */
        $response = $this->abstractResponseDataFactory->create();
        $response->setStatus(true)->setMessage(__("Currency changed successfully"));
        return $this->sendResponse($response);
    }
}
