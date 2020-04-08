<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Pwa\Observer;


use Codilar\Pwa\Api\PwaRepositoryInterface;
use Codilar\Pwa\Model\Config;
use Codilar\Pwa\Model\Redirect\HandlerPool;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\ResponseFactory;

class Redirect implements ObserverInterface
{
    const VARIABLE_BASE_URL = "%base_url%";
    const VARIABLE_PATH = "%path%";
    /**
     * @var PwaRepositoryInterface
     */
    private $pwaRepository;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var UrlInterface
     */
    private $url;
    /**
     * @var ResponseFactory
     */
    private $responseFactory;
    /**
     * @var HandlerPool
     */
    private $handlerPool;

    /**
     * Redirect constructor.
     * @param PwaRepositoryInterface $pwaRepository
     * @param Config $config
     * @param UrlInterface $url
     * @param ResponseFactory $responseFactory
     * @param HandlerPool $handlerPool
     */
    public function __construct(
        PwaRepositoryInterface $pwaRepository,
        Config $config,
        UrlInterface $url,
        ResponseFactory $responseFactory,
        HandlerPool $handlerPool
    )
    {
        $this->pwaRepository = $pwaRepository;
        $this->config = $config;
        $this->url = $url;
        $this->responseFactory = $responseFactory;
        $this->handlerPool = $handlerPool;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if ($this->config->getIsActive()) {
            /** @var Http $request */
            $request = $observer->getEvent()->getData('request');
            $fullActionName = $request->getFullActionName();
            $path = trim($request->getOriginalPathInfo(), '/');
            $params = $request->getParams();
            $handlers = $this->handlerPool->getHandlers();
            $redirectBaseUrl = $this->config->getRedirectBaseUrl();
            try {
                $redirectModel = $this->pwaRepository->load($fullActionName, 'request_url');
                if (in_array($fullActionName, array_keys($handlers))) {
                    $redirectionUrl = $handlers[$fullActionName]->handle($request, $redirectModel, $redirectBaseUrl);
                } else {
                    $redirectString = $redirectModel->getRedirectUrl();
                    $redirectUrl = str_replace(self::VARIABLE_BASE_URL, $redirectBaseUrl, $redirectString);
                    $redirectUrl = str_replace(self::VARIABLE_PATH, $path, $redirectUrl);
                    $redirectionUrl = $redirectUrl . '?' . http_build_query($params);
                }
                header(sprintf("Location: %s", $redirectionUrl));
                exit(0);
            } catch (NoSuchEntityException $e) {
            }
        }
    }
}