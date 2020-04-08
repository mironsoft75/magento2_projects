<?php

namespace Codilar\Core\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\HTTP\Client\Curl;

class GeoLocation extends AbstractHelper
{
    const PROIPAPIKEY = "kISHwVAcSnVh4Hm";

    /**
     * @var Curl
     */
    private $curl;

    /**
     * GeoLocation constructor.
     * @param Context $context
     * @param Curl $curl
     */
    public function __construct(
        Context $context,
        Curl $curl
    ) {
        parent::__construct($context);
        $this->curl = $curl;
    }

    /**
     * @param string $ipaddress
     * @return array
     */
    public function getCurrencyDetailsByIP($ipaddress)
    {
        $this->curl->get('https://pro.ip-api.com/json/' . $ipaddress . '?key=' . self::PROIPAPIKEY . '&fields=8388608');
        return json_decode($this->curl->getBody(), true);
    }
}
