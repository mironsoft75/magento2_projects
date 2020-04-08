<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Controller;


use Codilar\DynamicForm\Api\FormRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

class Router implements RouterInterface
{
    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;
    /**
     * @var SearchCriteriaBuilderFactory
     */
    private $searchCriteriaBuilderFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var FilterBuilder
     */
    private $filterBuilder;
    /**
     * @var ActionFactory
     */
    private $actionFactory;

    /**
     * Router constructor.
     * @param FormRepositoryInterface $formRepository
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param FilterBuilder $filterBuilder
     * @param StoreManagerInterface $storeManager
     * @param ActionFactory $actionFactory
     */
    public function __construct(
        FormRepositoryInterface $formRepository,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        FilterBuilder $filterBuilder,
        StoreManagerInterface $storeManager,
        ActionFactory $actionFactory
    )
    {
        $this->formRepository = $formRepository;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->storeManager = $storeManager;
        $this->filterBuilder = $filterBuilder;
        $this->actionFactory = $actionFactory;
    }

    /**
     * Match application action by request
     *
     * @param RequestInterface $request
     * @return ActionInterface
     */
    public function match(RequestInterface $request)
    {
        $identifier = trim($request->getPathInfo(), '/');
        try {
            $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
            $storeViewFilters = [
                $this->filterBuilder->setField('store_views')->setValue(\Codilar\DynamicForm\Ui\Component\Listing\Column\Source\Store::ALL_STORE_VIEWS)->setConditionType('finset')->create(),
                $this->filterBuilder->setField('store_views')->setValue($this->storeManager->getStore()->getId())->setConditionType('finset')->create()
            ];
            $searchCriteriaBuilder->addFilters($storeViewFilters);
            $searchCriteriaBuilder->addFilter('identifier', $identifier);
            $searchCriteriaBuilder->addFilter('is_active', 1);
            $formList = $this->formRepository->getList($searchCriteriaBuilder->create())->getItems();
            if (count($formList)) {
                $form = reset($formList);
                $request->setModuleName('dynamicform')->setControllerName('form')->setActionName('view')->setParam('id', $form->getId());
                $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $identifier);
                return $this->actionFactory->create(\Magento\Framework\App\Action\Forward::class);
            } else {
                throw NoSuchEntityException::singleField('identifier', $identifier);
            }
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }
}