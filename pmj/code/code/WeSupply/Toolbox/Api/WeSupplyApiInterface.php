<?php
namespace WeSupply\Toolbox\Api;


interface WeSupplyApiInterface
{

    /**
     * @param $externalOrderIdString
     * @return mixed
     */
    function weSupplyInterogation($externalOrderIdString);

    /**
     * @param $orderNo
     * @param $clientPhone
     * @return mixed
     */
    function notifyWeSupply($orderNo, $clientPhone);

    /**
     * @param $apiPath
     * @return mixed
     */
    function setApiPath($apiPath);

    /**
     * @param $apiClientId
     * @return mixed
     */
    function setApiClientId($apiClientId);

    /**
     * @param $apiClientSecret
     * @return mixed
     */
    function setApiClientSecret($apiClientSecret);
}
