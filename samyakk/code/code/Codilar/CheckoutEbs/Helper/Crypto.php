<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19/9/19
 * Time: 12:56 PM
 */

namespace Codilar\CheckoutEbs\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Psr\Log\LoggerInterface;

/**
 * Class Crypto
 *
 * @package Codilar\CheckoutEbs\Helper
 */
class Crypto extends AbstractHelper
{
    /**
     * Logger Interface
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Crypto constructor.
     *
     * @param LoggerInterface $logger
     * @param Context $context
     */
    public function __construct(
        LoggerInterface $logger,
        Context $context
    )
    {
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * DecryptResponse
     *
     * @param $response
     * @param $secret_key
     * @return string
     */

    public function decryptResponse($response, $secret_key)
    {
        try {
            $params = $secret_key;
            ksort($response);
            foreach ($response as $key => $value) {

                if (strlen($value) > 0 && $key != 'SecureHash') {
                    $params .= '|' . $value;
                }
            }

            return strtoupper(md5($params));
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }
}