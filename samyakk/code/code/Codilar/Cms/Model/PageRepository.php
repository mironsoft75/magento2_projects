<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17/4/19
 * Time: 12:41 PM
 */

namespace Codilar\Cms\Model;


use Codilar\Api\Api\AbstractApi;
use Codilar\Api\Helper\Cookie;
use Codilar\Cms\Api\Data\PageResponseInterface;
use Codilar\Cms\Api\Data\PageResponseInterfaceFactory;
use Codilar\Cms\Api\PageRepositoryInterface;
use Codilar\Cms\Helper\Filter;
use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Model\PageFactory;
use Magento\Cms\Model\ResourceModel\Page;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Webapi\Rest\Response;

class PageRepository extends AbstractApi implements PageRepositoryInterface
{
    /**
     * @var PageFactory
     */
    private $pageFactory;
    /**
     * @var Page
     */
    private $pageResource;
    /**
     * @var PageResponseInterfaceFactory
     */
    private $pageResponseInterfaceFactory;
    /**
     * @var Filter
     */
    private $filter;

    /**
     * PageRepository constructor.
     * @param Cookie $cookieHelper
     * @param RequestInterface $request
     * @param Response $response
     * @param Session $customerSession
     * @param DataObjectFactory $dataObjectFactory
     * @param PageFactory $pageFactory
     * @param Page $pageResource
     * @param PageResponseInterfaceFactory $pageResponseInterfaceFactory
     * @param Filter $filter
     */
    public function __construct(
        Cookie $cookieHelper,
        RequestInterface $request,
        Response $response,
        Session $customerSession,
        DataObjectFactory $dataObjectFactory,
        PageFactory $pageFactory,
        Page $pageResource,
        PageResponseInterfaceFactory $pageResponseInterfaceFactory,
        Filter $filter
    )
    {
        parent::__construct($cookieHelper, $request, $response, $customerSession, $dataObjectFactory);
        $this->pageFactory = $pageFactory;
        $this->pageResource = $pageResource;
        $this->pageResponseInterfaceFactory = $pageResponseInterfaceFactory;
        $this->filter = $filter;
    }

    /**
     * @param string $identifier
     * @return \Codilar\Cms\Api\Data\PageResponseInterface
     * @throws NoSuchEntityException
     */
    public function getByIdentifier($identifier)
    {
        try {
            if (!$identifier) {
                throw new LocalizedException(__("Page identifier can not be empty"));
            }
            $page = $this->pageFactory->create();
            $this->pageResource->load($page, $identifier, "identifier");
            /** @var PageResponseInterface $pageResponse */
            $pageResponse = $this->pageResponseInterfaceFactory->create();
            /** @var PageInterface $page */
            if ($page->getId() && $page->isActive()) {
                $content = $this->filter->filterStaticContent($page->getContent());
                $pageResponse->setStatus(true)
                    ->setId($page->getId())
                    ->setTitle($page->getTitle())
                    ->setMetaDescription($page->getMetaDescription())
                    ->setContent($content);
                return $this->sendResponse($pageResponse);
            } else {
                throw new LocalizedException(__("Page doesn't exist"));
            }
        } catch (LocalizedException $localizedException) {
            throw NoSuchEntityException::singleField("id", $identifier);
        }
    }
}