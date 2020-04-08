<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Store\Plugin\Model;

use Codilar\Store\Api\CurrencySwitcherRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\Store as Subject;
use Codilar\Api\Helper\Customer as CustomerApiHelper;

class Store
{
    /**
     * @var CurrencySwitcherRepositoryInterface
     */
    private $currencySwitcherRepository;
    /**
     * @var CustomerApiHelper
     */
    private $customerApiHelper;

    /**
     * Store constructor.
     * @param CurrencySwitcherRepositoryInterface $currencySwitcherRepository
     * @param CustomerApiHelper $customerApiHelper
     */
    public function __construct(
        CurrencySwitcherRepositoryInterface $currencySwitcherRepository,
        CustomerApiHelper $customerApiHelper
    )
    {
        $this->currencySwitcherRepository = $currencySwitcherRepository;
        $this->customerApiHelper = $customerApiHelper;
    }

    /**
     * @param Subject $subject
     * @param callable $proceed
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function aroundGetCurrentCurrencyCode(Subject $subject, callable $proceed) {
        try {
            $quote = $this->customerApiHelper->getCustomerQuoteByToken();
            $currencyModel = $this->currencySwitcherRepository->load($quote->getId(), 'quote_id');
            $result = $currencyModel->getUpdateCurrencyTo();
            $this->currencySwitcherRepository->delete($currencyModel);
        } catch (NoSuchEntityException $e) {
            $result = $proceed();
        }

        return $result;
    }
}