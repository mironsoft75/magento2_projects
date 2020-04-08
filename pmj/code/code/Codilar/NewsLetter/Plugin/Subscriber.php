<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17/1/19
 * Time: 12:56 PM
 */

namespace Codilar\NewsLetter\Plugin;

use Magento\Framework\App\Request\Http;

/**
 * Class Subscriber
 * @package Codilar\NewsLetter\Plugin
 */
class Subscriber
{
    /**
     * @var Http
     */
    protected $request;

    /**
     * Subscriber constructor.
     * @param Http $request
     */
    public function __construct(Http $request)
    {
        $this->request = $request;
    }

    /**
     * @param $subject
     * @param \Closure $proceed
     * @param $email
     * @return mixed
     * @throws \Exception
     */
    public function aroundSubscribe($subject, \Closure $proceed, $email)
    {
        $result = $proceed($email);
        if ($this->request->isPost() && $this->request->getPost('gender')) {
            $gender = $this->request->getPost('gender');
            $subject->setGender($gender);
            try {
                $subject->save();
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }
        return $result;
    }
}