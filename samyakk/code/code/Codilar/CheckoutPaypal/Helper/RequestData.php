<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutPaypal\Helper;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;

class RequestData
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * RequestData constructor.
     * @param OrderRepositoryInterface $orderRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        StoreManagerInterface $storeManager
    ) {
        $this->orderRepository = $orderRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * @param int $orderId
     * @return false|string
     * @throws NoSuchEntityException
     */
    public function getRequestData($orderId)
    {
        $requestData = [];
        $baseUrl = $this->storeManager->getStore()->getBaseUrl();
        /** @var OrderInterface $order */
        $order = $this->orderRepository->get($orderId);
        $requestData = [
            'intent' => 'sale',
            'payer' => [
                    'payment_method' => 'paypal',
                ],
            'transactions' => [
                        [
                            'amount' => $this->getAmount($order),
                            'description' => 'This is the payment transaction description of customer name ' . $order->getCustomerFirstname(),
                            'invoice_number' => $orderId,
                            'payment_options' => [
                                    'allowed_payment_method' => 'INSTANT_FUNDING_SOURCE',
                                ],
                            'item_list' => [
                                    'items' => $this->getOrderItems($order),
                                    'shipping_address' => $this->getShippingAddress($order)
                                ],
                        ],
                ],
            'note_to_payer' => 'Contact us for any questions on your order.',
            'redirect_urls' => [
                    'return_url' => $baseUrl . 'checkoutpaypal/response/success',
                    'cancel_url' => $baseUrl . 'checkoutpaypal/response/failure'
                ],
        ];

        return json_encode($requestData);
    }

    /**
     * @param OrderInterface $order
     * @return array
     */
    public function getOrderItems($order)
    {
        /** @var OrderInterface $order */
        $items = $order->getItems();
        $itemsArray = [];
        foreach ($items as $item) {
            if ($item->getParentItemId()) {
                continue;
            }
            $itemArray = [
                'name' => $item->getName(),
                'quantity' => (int)$item->getQtyOrdered(),
                'price' => number_format($item->getPrice(), 2),
                'sku' => $item->getSku(),
                'currency' => $order->getOrderCurrencyCode()
            ];
            $itemsArray[] = $itemArray;
        }
        if ($order->getDiscountAmount() < 0) {
            $itemArray = [
                'name' => "Discount",
                'quantity' => 1,
                'price' => number_format($order->getDiscountAmount(), 2),
                'sku' => 'discount',
                'currency' => $order->getOrderCurrencyCode()
            ];
            $itemsArray[] = $itemArray;
        }
        return $itemsArray;
    }

    /**
     * @param OrderInterface $order
     * @return array
     */
    public function getShippingAddress($order)
    {
        /** @var OrderInterface $order */
        $shippingAddress = $order->getShippingAddress();
        $street = $shippingAddress->getStreet();

        $address = [
            'recipient_name' => $shippingAddress->getFirstname() . " " . $shippingAddress->getLastName(),
            'line1' => $street[0],
            'line2' => isset($street[2]) ? $street[1] . $street[2] : isset($street[1]) ? $street[1] : "",
            'city' => $shippingAddress->getCity(),
            'country_code' => $shippingAddress->getCountryId(),
            'postal_code' => $shippingAddress->getPostCode(),
            'phone' => $shippingAddress->getTelephone(),
            'state' => $shippingAddress->getRegionCode()
        ];
        return $address;
    }

    /**
     * @param OrderInterface $order
     * @return array
     */
    public function getAmount($order)
    {
        /** @var OrderInterface $order */
        $amountDetails = [
            'total' => number_format($order->getGrandTotal(), 2),
            'currency' => $order->getOrderCurrencyCode(),
            'details' => [
                'subtotal' => number_format($order->getSubtotal() + $order->getDiscountAmount(), 2),
                'tax' => number_format($order->getTaxAmount(), 2),
                'shipping' => number_format($order->getShippingAmount(), 2),
                'handling_fee' => '0.00',
//                'discount' => abs(number_format($order->getDiscountAmount(), 2)),
                'shipping_discount' => number_format($order->getShippingDiscountAmount(), 2),
                'insurance' => '0.00',
            ],
        ];
        return $amountDetails;
    }

    /**
     * @param string $url
     * @return mixed
     */
    public function getToken($url)
    {
        $url = parse_url($url);
        parse_str($url['query'], $output);
        return $output['token'];
    }

    /**
     * @param string $payerId
     * @return string
     */
    public function getPaypalConformationRequestData($payerId)
    {
        return '{
          "payer_id": "' . $payerId . '"
        }';
    }
}
