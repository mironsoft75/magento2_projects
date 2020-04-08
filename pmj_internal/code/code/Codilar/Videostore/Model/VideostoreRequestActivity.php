<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 22/11/18
 * Time: 2:50 PM
 */

namespace Codilar\Videostore\Model;


use Codilar\Videostore\Api\Data\VideostoreRequestActivityInterface;

class VideostoreRequestActivity  extends \Magento\Framework\Model\AbstractModel implements VideostoreRequestActivityInterface
{

    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'codilar_videostore_request_activity';

    /**
     * @var string
     */
    protected $_cacheTag = 'codilar_videostore_request_activity';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'codilar_videostore_request_activity';

    protected function _construct()
    {
        $this->_init('Codilar\Videostore\Model\ResourceModel\VideostoreRequestActivity');
    }

    /**
     * @return mixed
     */
    public function getVideostoreRequestActivityId()
    {
        return $this->getData(self::VIDEOSTORE_REQUEST_ACTIVITY_ID);
    }

    /**
     * @param int $videostore_request_activity_id
     * @return mixed
     */
    public function setVideostoreRequestActivityId($videostore_request_activity_id)
    {
        return $this->setData(self::VIDEOSTORE_REQUEST_ACTIVITY_ID, $videostore_request_activity_id);
    }

    /**
     * @return mixed
     */
    public function getVideostoreRequestId()
    {
        return $this->getData(self::VIDEOSTORE_REQUEST_ID);
    }

    /**
     * @param int $videostore_request_id
     * @return mixed
     */
    public function setVideostoreRequestId($videostore_request_id)
    {
        return $this->setData(self::VIDEOSTORE_REQUEST_ID, $videostore_request_id);
    }

    /**
     * @return mixed
     */
    public function getVideostoreRequestActivity()
    {
        return $this->getData(self::VIDEOSTORE_REQUEST_ACTIVITY);
    }

    /**
     * @param string $videostore_request_activity
     * @return mixed
     */
    public function setVideostoreRequestActivity($videostore_request_activity)
    {
        return $this->setData(self::VIDEOSTORE_REQUEST_ACTIVITY, $videostore_request_activity);
    }


}