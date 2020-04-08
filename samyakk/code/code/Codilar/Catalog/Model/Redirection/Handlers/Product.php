<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Catalog\Model\Redirection\Handlers;


use Codilar\Pwa\Model\Pwa as RedirectionModel;
use Codilar\Pwa\Model\Redirect\HandlerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Registry;

class Product implements HandlerInterface
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * Product constructor.
     * @param Registry $registry
     */
    public function __construct(
        Registry $registry
    )
    {
        $this->registry = $registry;
    }

    /**
     * @param RequestInterface $request
     * @param RedirectionModel $redirectionModel
     * @param string $redirectBaseUrl
     * @return string
     */
    public function handle(RequestInterface $request, RedirectionModel $redirectionModel, $redirectBaseUrl)
    {
        /** @var \Magento\Catalog\Model\Product $currentProduct */
        $currentProduct = $this->registry->registry('current_product');
        if ($currentProduct) {
            $redirectBaseUrl = sprintf('%s/%s/%s', $redirectBaseUrl, $currentProduct->getSku(), $currentProduct->getUrlKey());
        }
        return $redirectBaseUrl;
    }
}