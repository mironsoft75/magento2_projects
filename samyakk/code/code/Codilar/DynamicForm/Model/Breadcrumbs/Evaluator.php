<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Model\Breadcrumbs;


use Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsTypeEvaluatorInterface;
use Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsResponseDataInterface;
use Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsResponseDataInterfaceFactory;
use Codilar\Breadcrumbs\Helper\BreadcrumbHome;
use Codilar\DynamicForm\Api\FormRepositoryInterface;


class Evaluator implements BreadcrumbsTypeEvaluatorInterface
{

    /**
     * @var BreadcrumbsResponseDataInterfaceFactory
     */
    private $breadcrumbsResponseDataInterfaceFactory;
    /**
     * @var BreadcrumbHome
     */
    private $breadcrumbHome;
    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;

    /**
     * Evaluator constructor.
     * @param BreadcrumbsResponseDataInterfaceFactory $breadcrumbsResponseDataInterfaceFactory
     * @param BreadcrumbHome $breadcrumbHome
     * @param FormRepositoryInterface $formRepository
     */
    public function __construct(
        BreadcrumbsResponseDataInterfaceFactory $breadcrumbsResponseDataInterfaceFactory,
        BreadcrumbHome $breadcrumbHome,
        FormRepositoryInterface $formRepository
    )
    {
        $this->breadcrumbsResponseDataInterfaceFactory = $breadcrumbsResponseDataInterfaceFactory;
        $this->breadcrumbHome = $breadcrumbHome;
        $this->formRepository = $formRepository;
    }

    /**
     * @param int $id
     * @return \Codilar\Breadcrumbs\Api\Data\Breadcrumbs\BreadcrumbsResponseDataInterface[]
     */
    public function getBreadcrumbs($id)
    {
        $responseArray = [];
        $responseArray[] = $this->breadcrumbHome->getHomeData();
        $form = $this->formRepository->load($id);
        /** @var BreadcrumbsResponseDataInterface $response */
        $response = $this->breadcrumbsResponseDataInterfaceFactory->create();
        $response->setTitle($form->getTitle())->setLink("");
        $responseArray[] = $response;
        return $responseArray;
    }
}