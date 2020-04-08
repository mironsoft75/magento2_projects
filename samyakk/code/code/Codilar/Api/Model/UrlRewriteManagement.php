<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Api\Model;

use Codilar\Api\Api\AbstractApi;
use Codilar\Api\Api\Data\EntityDataInterface;
use Codilar\Api\Api\Data\EntityDataInterfaceFactory;
use Codilar\Api\Api\UrlRewriteManagementInterface;
use Codilar\Api\Helper\Cookie;
use Codilar\Api\Helper\UrlKey;
use Codilar\Meta\Api\MetaDataManagementInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Product as ProductResource;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Webapi\Rest\Response;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewrite as UrlRewriteResource;
use Magento\UrlRewrite\Model\UrlRewriteFactory;
use Zend_Db_Statement_Exception;

class UrlRewriteManagement extends AbstractApi implements UrlRewriteManagementInterface
{
    /**
     * @var EntityDataInterfaceFactory
     */
    private $entityDataInterfaceFactory;
    /**
     * @var UrlRewriteFactory
     */
    private $urlRewriteFactory;
    /**
     * @var UrlRewriteResource
     */
    private $urlRewriteResource;
    /**
     * @var ProductFactory
     */
    private $productFactory;
    /**
     * @var ProductResource
     */
    private $productResource;
    /**
     * @var MetaDataManagementInterface
     */
    private $metaDataManagement;
    /**
     * @var UrlKey
     */
    private $urlKey;

    /**
     * UrlRewriteManagement constructor.
     * @param Cookie $cookieHelper
     * @param RequestInterface $request
     * @param Response $response
     * @param Session $customerSession
     * @param DataObjectFactory $dataObjectFactory
     * @param EntityDataInterfaceFactory $entityDataInterfaceFactory
     * @param UrlRewriteFactory $urlRewriteFactory
     * @param UrlRewriteResource $urlRewriteResource
     * @param ProductFactory $productFactory
     * @param ProductResource $productResource
     * @param MetaDataManagementInterface $metaDataManagement
     * @param UrlKey $urlKey
     */
    public function __construct(
        Cookie $cookieHelper,
        RequestInterface $request,
        Response $response,
        Session $customerSession,
        DataObjectFactory $dataObjectFactory,
        EntityDataInterfaceFactory $entityDataInterfaceFactory,
        UrlRewriteFactory $urlRewriteFactory,
        UrlRewriteResource $urlRewriteResource,
        ProductFactory $productFactory,
        ProductResource $productResource,
        MetaDataManagementInterface $metaDataManagement,
        UrlKey $urlKey
    ) {
        parent::__construct($cookieHelper, $request, $response, $customerSession, $dataObjectFactory);
        $this->entityDataInterfaceFactory = $entityDataInterfaceFactory;
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->urlRewriteResource = $urlRewriteResource;
        $this->productFactory = $productFactory;
        $this->productResource = $productResource;
        $this->metaDataManagement = $metaDataManagement;
        $this->urlKey = $urlKey;
    }

    /**
     * @param string $slug
     * @return EntityDataInterface
     * @throws NoSuchEntityException
     */
    public function getEntityDataBySlug($slug)
    {
        $slug = trim($slug, '/');
        $entityDataInterface = $this->getEntityInfoByUrlKey($slug);
        switch ($entityDataInterface->getType()) {
            case 'cms-page':
                $metaData = $this->metaDataManagement->getMetaData('cms_page', $entityDataInterface->getIdentifier());
                $entityDataInterface->setIdentifier($slug);
                break;
            case 'category':
                $metaData = $this->metaDataManagement->getMetaData('category', $entityDataInterface->getIdentifier());
                break;
            case 'product':
                $metaData = $this->metaDataManagement->getMetaData('product', $slug);
                $urlParts = explode('/', $slug);
                $entityDataInterface->setIdentifier($urlParts[count($urlParts) - 1]);
                break;
            default:
                throw NoSuchEntityException::singleField('type', $entityDataInterface->getType());
        }
        $entityDataInterface->setMetaData($metaData);
        return $this->sendResponse($entityDataInterface);
    }

    protected function getProductIdentifier($id)
    {
        try {
            $product = $this->getProduct($id);
            return "/" . $product->getUrlKey();
        } catch (NoSuchEntityException $e) {
            return '/';
        }
    }

    /**
     * @param $urlKey
     * @return EntityDataInterface|null
     * @throws NoSuchEntityException
     */
    private function getEntityInfoByUrlKey($urlKey)
    {
        try {
            $urlKeyResult = $this->urlKey->getEntityIdByUrlRewrite($urlKey);
            $entityDataInterface = $this->entityDataInterfaceFactory->create();
            if ($urlKeyResult && is_array($urlKeyResult)) {
                if ($urlKeyResult['entity_type'] == "cms-page") {
                    $entityDataInterface = $this->getEntityByUrlKeyAttribute($urlKey, $entityDataInterface);
                } else {
                    if ($urlKeyResult['entity_id'] != 0) {
                        $entityDataInterface->setType($urlKeyResult['entity_type']);
                        $entityDataInterface->setIdentifier($urlKeyResult['entity_id']);
                    } else {
                        $entityDataInterface = $this->getEntityByUrlKeyAttribute($urlKeyResult['target_path'], $entityDataInterface);
                    }
                }
            } else {
                $entityDataInterface = $this->getEntityByUrlKeyAttribute($urlKey, $entityDataInterface);
            }
            return $entityDataInterface;
        } catch (Zend_Db_Statement_Exception $e) {
        }
        return null;
    }

    /**
     * @param int $id
     * @return Product
     * @throws NoSuchEntityException
     */
    protected function getProduct($id)
    {
        $product = $this->productFactory->create();
        $this->productResource->load($product, $id);
        if (!$product->getId()) {
            throw NoSuchEntityException::singleField('id', $id);
        }
        return $product;
    }

    /**
     * @param string $urlKey
     * @param EntityDataInterface $entityDataInterface
     * @return EntityDataInterface
     * @throws NoSuchEntityException
     */
    private function getEntityByUrlKeyAttribute($urlKey, EntityDataInterface $entityDataInterface)
    {
        $urlKeyData = $this->urlKey->getUrlKeyData($urlKey);
        /** @var EntityDataInterface $entityDataInterface */
        $entityDataInterface->setType($urlKeyData['type']);
        $entityDataInterface->setIdentifier($urlKeyData['id']);
        return $entityDataInterface;
    }
}
