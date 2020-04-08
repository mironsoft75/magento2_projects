<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Pwa\Model;


use Codilar\Pwa\Api\Data\PwaInterface;
use Magento\Framework\Model\AbstractModel;
use Codilar\Pwa\Model\ResourceModel\Pwa as ResourceModel;

class Pwa extends AbstractModel implements PwaInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @return int
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * @return string
     */
    public function getRequestUrl()
    {
        return $this->getData(self::REQUEST_URL);
    }

    /**
     * @param string $reuestUrl
     * @return $this
     */
    public function setRequestUrl($reuestUrl)
    {
        return $this->setData(self::REQUEST_URL, $reuestUrl);
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->getData(self::REDIRECT_URL);
    }

    /**
     * @param string $redirectUrl
     * @return $this
     */
    public function setRedirectUrl($redirectUrl)
    {
        return $this->setData(self::REDIRECT_URL, $redirectUrl);
    }
}