<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Logo\Model;

use Codilar\Api\Api\AbstractApi;
use Codilar\Api\Helper\Cookie;
use Codilar\Logo\Api\Data\LogoInterface;
use Codilar\Logo\Api\LogoRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Webapi\Rest\Response;

class LogoRepository extends AbstractApi implements LogoRepositoryInterface
{
    /**
     * @var LogoInterface
     */
    private $logoInterface;

    /**
     * LogoRepository constructor.
     * @param Cookie $cookieHelper
     * @param RequestInterface $request
     * @param Response $response
     * @param Session $customerSession
     * @param DataObjectFactory $dataObjectFactory
     * @param LogoInterface $logoInterface
     */
    public function __construct(
        Cookie $cookieHelper,
        RequestInterface $request,
        Response $response,
        Session $customerSession,
        DataObjectFactory $dataObjectFactory,
        LogoInterface $logoInterface
    )
    {
        parent::__construct($cookieHelper, $request, $response, $customerSession, $dataObjectFactory);
        $this->logoInterface = $logoInterface;
    }

    /**
     * @return LogoInterface
     */
    public function getLogo()
    {
        $logo = [
            "logo_src" => $this->logoInterface->getLogoSrc(),
            "logo_height" => $this->logoInterface->getLogoHeight(),
            "logo_width" => $this->logoInterface->getLogoWidth(),
            "logo_alt" => $this->logoInterface->getLogoAlt()
        ];

        return $this->sendResponse($this->getNewDataObject($logo));
    }
}
