<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Controller\Adminhtml\Element;


use Codilar\DynamicForm\Api\Form\ElementRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Ui\Component\MassAction\Filter;
use Codilar\DynamicForm\Model\ResourceModel\Form\Element\CollectionFactory;


class MassDelete extends Action
{
    const ADMIN_RESOURCE = "Codilar_DynamicForm::form_element";
    /**
     * @var ElementRepositoryInterface
     */
    private $elementRepository;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var Filter
     */
    private $filter;

    /**
     * MassDelete constructor.
     * @param Action\Context $context
     * @param Filter $filter
     * @param ElementRepositoryInterface $elementRepository
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Action\Context $context,
        Filter $filter,
        ElementRepositoryInterface $elementRepository,
        CollectionFactory $collectionFactory
    )
    {
        parent::__construct($context);
        $this->elementRepository = $elementRepository;
        $this->collectionFactory = $collectionFactory;
        $this->filter = $filter;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        foreach ($collection as $item) {
            $this->elementRepository->delete($this->elementRepository->getDataModel($item));
        }
        $this->messageManager->addSuccessMessage(__("A total of %1 record(s) were deleted", count($collection)));
        return $this->resultRedirectFactory->create()->setPath('*/*');
    }
}