<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 22/11/18
 * Time: 4:52 PM
 */

namespace Codilar\Videostore\Api\Data;


interface VideostoreRequestActivityInterface
{

    const VIDEOSTORE_REQUEST_ACTIVITY_ID  = 'videostore_request_activity_id';
    const VIDEOSTORE_REQUEST_ID = 'videostore_request_id';
    const VIDEOSTORE_REQUEST_ACTIVITY  = 'videostore_request_activity';

    /**
     * @return mixed
     */
    public function getVideostoreRequestActivityId();

    /**
     * @param integer $videostore_request_activity_id
     */
    public function setVideostoreRequestActivityId($videostore_request_activity_id);

    /**
     * @return mixed
     */
    public function getVideostoreRequestId();

    /**
     * @param integer $videostore_request_id
     */
    public function setVideostoreRequestId($videostore_request_id);

    /**
     * @return mixed
     */
    public function getVideostoreRequestActivity();

    /**
     * @param string $videostore_request_activity
     */
    public function setVideostoreRequestActivity($videostore_request_activity);

}