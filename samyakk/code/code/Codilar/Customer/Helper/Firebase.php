<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Customer\Helper;


use Codilar\Customer\Model\Config;
use Kreait\Firebase\ServiceAccount;
use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Factory;
use Magento\Framework\Exception\LocalizedException;

class Firebase
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var Factory
     */
    private $factory;

    /**
     * Firebase constructor.
     * @param Config $config
     * @param Factory $factory
     */
    public function __construct(
        Config $config,
        Factory $factory
    )
    {
        $this->config = $config;
        $this->factory = $factory;
    }

    /**
     * @return ServiceAccount
     */
    public function getServiceAccount()
    {
        return ServiceAccount::fromArray(\json_decode($this->config->getServiceAccountJson(), true));
    }

    /**
     * @return \Kreait\Firebase
     */
    public function getFirebase()
    {
        return $this->factory->withServiceAccount($this->getServiceAccount())->create();
    }

    /**
     * @param string $token
     * @return \Kreait\Firebase\Auth\UserRecord
     * @throws LocalizedException
     */
    public function getUserByToken($token)
    {
        $firebase = $this->getFirebase();
        try {
            $verifiedIdToken = $firebase->getAuth()->verifyIdToken($token);
            $userId = $verifiedIdToken->getClaim('sub');
            return $firebase->getAuth()->getUser($userId);
        } catch (\Exception $exception) {
            throw new LocalizedException(__($exception->getMessage()));
        }
    }
}