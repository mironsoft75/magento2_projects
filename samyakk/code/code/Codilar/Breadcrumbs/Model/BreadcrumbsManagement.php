<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Breadcrumbs\Model;


use Codilar\Breadcrumbs\Api\BreadcrumbsManagementInterface;
use Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsResponseDataInterface;
use Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsResponseDataInterfaceFactory;
use Codilar\Breadcrumbs\Api\Data\BreadcrumbsResponseInterface;
use Codilar\Breadcrumbs\Api\Data\BreadcrumbsResponseInterfaceFactory;
use Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsTypeEvaluatorInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class BreadcrumbsManagement implements BreadcrumbsManagementInterface
{
    /**
     * @var array
     */
    private $breadcumbsTypeEvaluator;
    /**
     * @var BreadcrumbsResponseInterface
     */
    private $breadcrumbsResponse;
    /**
     * @var BreadcrumbsResponseInterfaceFactory
     */
    private $breadcrumbsResponseInterfaceFactory;
    /**
     * @var BreadcrumbsResponseDataInterfaceFactory
     */
    private $breadcrumbsResponseDataInterfaceFactory;

    /**
     * BreadcrumbsManagement constructor.
     * @param BreadcrumbsResponseInterfaceFactory $breadcrumbsResponseInterfaceFactory
     * @param BreadcrumbsResponseDataInterfaceFactory $breadcrumbsResponseDataInterfaceFactory
     * @param array $breadcumbsTypeEvaluator
     */
    public function __construct(
        BreadcrumbsResponseInterfaceFactory $breadcrumbsResponseInterfaceFactory,
        BreadcrumbsResponseDataInterfaceFactory $breadcrumbsResponseDataInterfaceFactory,
        array $breadcumbsTypeEvaluator = []
    )
    {
        $this->breadcrumbsResponseInterfaceFactory = $breadcrumbsResponseInterfaceFactory;
        $this->breadcrumbsResponseDataInterfaceFactory = $breadcrumbsResponseDataInterfaceFactory;
        $this->breadcumbsTypeEvaluator = $breadcumbsTypeEvaluator;
    }

    /**
     * @param string $type
     * @param int $id
     * @return \Codilar\Breadcrumbs\Api\Data\BreadcrumbsResponseInterface
     */
    public function getBreadcrumbs($type, $id)
    {
        /** @var BreadcrumbsResponseInterface $response */
        $response = $this->breadcrumbsResponseInterfaceFactory->create();
        $responseArray = [];
        try {
            $evaluator = $this->getEvaluatorInstanceByType($type);
            $data = $evaluator->getBreadcrumbs($id);
            /** @var BreadcrumbsResponseDataInterface $item */
            foreach ($data as $item) {
                /** @var BreadcrumbsResponseDataInterface $responseData */
                $responseData = $this->breadcrumbsResponseDataInterfaceFactory->create();
                $responseData->setTitle($item->getTitle())
                    ->setLink($item->getLink());
                $responseArray[] = $responseData;
            }
            $response->setBreadcrumb($responseArray);
        } catch (NoSuchEntityException $e) {
        }
        return $response;
    }

    /**
     * @param string $type
     * @return BreadcrumbsTypeEvaluatorInterface
     * @throws NoSuchEntityException
     */
    protected function getEvaluatorInstanceByType($type) {
        if (array_key_exists($type, $this->breadcumbsTypeEvaluator)) {
            if ($this->breadcumbsTypeEvaluator[$type] instanceof BreadcrumbsTypeEvaluatorInterface) {
                return $this->breadcumbsTypeEvaluator[$type];
            }
        }
        throw NoSuchEntityException::singleField('type', $type);
    }
}