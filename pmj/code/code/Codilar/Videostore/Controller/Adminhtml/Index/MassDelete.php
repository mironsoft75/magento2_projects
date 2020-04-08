<?php
/**
 * @package     eat
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Videostore\Controller\Adminhtml\Index;

use Codilar\Videostore\Model\ResourceModel\VideostoreRequest\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;


class massDelete extends Action
{

    /**
     * @var Filter
     */
    private $filter;
    /**
     * @var CollectionFactory
     */
    private $requestCollection;

    /**
     * massDelete constructor.
     * @param Action\Context $context
     * @param Filter $filter
     * @param CollectionFactory $requestCollection
     */
    public function __construct(
        Action\Context $context,
        Filter $filter,
        CollectionFactory $requestCollection)
    {
        parent::__construct($context);
        $this->filter = $filter;
        $this->requestCollection = $requestCollection;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->requestCollection->create());
            $collectionSize = $collection->count();
            foreach ($collection as $request) {
               $request->delete();
            }
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));
        } catch (LocalizedException $localizedException) {
            $this->messageManager->addErrorMessage($localizedException->getMessage());
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__("SOme error occurred while deleting the record(s)"));
        }
        return $resultRedirect = $this->resultRedirectFactory->create()->setPath('*/*');
    }
}