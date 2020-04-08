<?php
/**
 * @package     eat
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Timeslot\Controller\Adminhtml\Index;


use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Codilar\Timeslot\Api\TimeslotRepositoryInterface;
use Codilar\Timeslot\Model\ResourceModel\Timeslot as TimeslotResource;
use Magento\Framework\Exception\LocalizedException;

class Delete extends Action
{
    /**
     * @var TimeslotRepositoryInterface
     */
    private $timeslotRepository;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param TimeslotRepositoryInterface $timeslotRepository
     */
    public function __construct(
        Action\Context $context,
        TimeslotRepositoryInterface $timeslotRepository)
    {
        parent::__construct($context);
        $this->timeslotRepository = $timeslotRepository;
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
        $id = $this->getRequest()->getParam(TimeslotResource::ID_FIELD);
        try {
            $model = $this->timeslotRepository->load($id);
            $this->timeslotRepository->delete($model);
            $this->messageManager->addSuccessMessage(__("Timeslot deleted successfully"));
        } catch (LocalizedException $localizedException) {
            $this->messageManager->addErrorMessage($localizedException->getMessage());
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__("Some error occured while deleting the timeslot."));
        }
        return $this->resultRedirectFactory->create()->setPath('*/*');
    }
}