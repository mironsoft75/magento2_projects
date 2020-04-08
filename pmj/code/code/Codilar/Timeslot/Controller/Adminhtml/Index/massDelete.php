<?php
/**
 * @package     eat
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Timeslot\Controller\Adminhtml\Index;

use Codilar\Timeslot\Api\TimeslotRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;


class massDelete extends Action
{
    /**
     * @var TimeslotRepositoryInterface
     */
    private $timeslotRepository;
    /**
     * @var Filter
     */
    private $filter;

    /**
     * massDelete constructor.
     * @param Action\Context $context
     * @param Filter $filter
     * @param TimeslotRepositoryInterface $timeslotRepository
     */
    public function __construct(
        Action\Context $context,
        Filter $filter,
        TimeslotRepositoryInterface $timeslotRepository)
    {
        parent::__construct($context);
        $this->timeslotRepository = $timeslotRepository;
        $this->filter = $filter;
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
            $collection = $this->filter->getCollection($this->timeslotRepository->getCollection());
            $collectionSize = $collection->count();
            foreach ($collection as $timeslot) {
                $this->timeslotRepository->delete($timeslot);
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