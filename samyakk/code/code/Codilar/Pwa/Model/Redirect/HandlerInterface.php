<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Pwa\Model\Redirect;


use Codilar\Pwa\Model\Pwa as RedirectionModel;
use Magento\Framework\App\RequestInterface;

interface HandlerInterface
{
    /**
     * @param RequestInterface $request
     * @param RedirectionModel $redirectionModel
     * @param string $redirectBaseUrl
     * @return string
     */
    public function handle(RequestInterface $request, RedirectionModel $redirectionModel, $redirectBaseUrl);
}