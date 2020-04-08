<?php
/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Api\Helper;


use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Integration\Model\Oauth\Token;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;

class Customer extends AbstractHelper
{
    /**
     * @var Token
     */
    private $token;
    /**
     * @var CustomerFactory
     */
    private $customerFactory;
    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer
     */
    private $customerResource;
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;
    /**
     * @var \Magento\Quote\Model\QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;
    /**
     * @var CartManagementInterface
     */
    private $cartManagement;

    /**
     * Customer constructor.
     * @param Context $context
     * @param Token $token
     * @param CustomerFactory $customerFactory
     * @param \Magento\Customer\Model\ResourceModel\Customer $customerResource
     * @param CartRepositoryInterface $cartRepository
     * @param \Magento\Quote\Model\QuoteIdMaskFactory $quoteIdMaskFactory
     * @param CartManagementInterface $cartManagement
     */
    public function __construct(
        Context $context,
        Token $token,
        CustomerFactory $customerFactory,
        \Magento\Customer\Model\ResourceModel\Customer $customerResource,
        CartRepositoryInterface $cartRepository,
        \Magento\Quote\Model\QuoteIdMaskFactory $quoteIdMaskFactory,
        CartManagementInterface $cartManagement
    )
    {
        parent::__construct($context);
        $this->token = $token;
        $this->customerFactory = $customerFactory;
        $this->customerResource = $customerResource;
        $this->cartRepository = $cartRepository;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->cartManagement = $cartManagement;
    }

    /**
     * @param string|null $token
     * @return \Magento\Quote\Api\Data\CartInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function getCustomerQuoteByToken($token = null)
    {
        $customerId = $this->getCustomerIdByToken(false, $token);
        if ($customerId) {
            try {
                return $this->cartRepository->getActiveForCustomer($customerId);
            } catch (NoSuchEntityException $e) {
                $cartId = $this->cartManagement->createEmptyCartForCustomer($customerId);
                $quote =  $this->cartRepository->get($cartId);
                $quote->setIsActive(true);
                $this->cartRepository->save($quote);
                return $quote;
            }
        } else {
            throw NoSuchEntityException::singleField('cartId', null);
        }
    }

    /**
     * Returns Customer Id if token is present in authorization header
     * @param bool $sendModel
     * @param null|string $token
     * @return bool|int|\Magento\Customer\Model\Customer
     */
    public function getCustomerIdByToken($sendModel = false, $token = null)
    {
        if (!$token) {
            $token = $this->getToken();
        }
        if ($token) {
            $tokenModel = $this->token->loadByToken($token);
            if ($sendModel) {
                return $this->getCustomer($tokenModel->getCustomerId());
            } else {
                return $tokenModel->getCustomerId();
            }
        }
        return false;
    }

    /**
     * @param int $customerId
     * @return bool
     */
    public function deleteCustomerToken($customerId)
    {
        try {
            $this->token->loadByCustomerId($customerId)->delete();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param int $customerId
     * @return \Magento\Customer\Model\Customer
     */
    public function getCustomer($customerId)
    {
        $customer = $this->customerFactory->create();
        $this->customerResource->load($customer, $customerId);
        return $customer;
    }

    /**
     * @return \Magento\Quote\Api\Data\CartInterface
     * @throws NoSuchEntityException
     */
    public function getActiveQuote()
    {
        try {
            $quote = $this->getCustomerQuoteByToken();
        } catch (NoSuchEntityException $e) {
            $quoteId = $this->quoteIdMaskFactory->create()->load($this->_getRequest()->getParam('cartId', $this->_getRequest()->getParam('cart_id', null)), 'masked_id')->getData('quote_id');
            $quote = $this->cartRepository->get($quoteId);
        }
        return $quote;
    }

    /**
     * @return bool|string
     */
    public function getToken()
    {
        $token = $this->_request->getHeader("Authorization");
        $token = explode(" ", $token);
        if (isset($token[count($token)-1])) {
            $token = $token[count($token)-1];
        } else {
            $token = false;
        }
        return $token;
    }
}