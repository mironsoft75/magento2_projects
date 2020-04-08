<?php
/**
 * @package     eat
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Timeslot\Controller\Adminhtml\Index;


use Codilar\Timeslot\Api\TimeslotRepositoryInterface;
use Codilar\Timeslot\Model\Timeslot;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

class massDisable extends Action
{
    /**
     * @var Filter
     */
    private $filter;
    /**
     * @var TimeslotRepositoryInterface
     */
    private $timeslotRepository;

    /**
     * massDisable constructor.
     * @param Action\Context $context
     * @param Filter $filter
     * @param TimeslotRepositoryInterface $timeslotRepository
     */
    public function __construct(
        Action\Context $context,
        Filter $filter,
        TimeslotRepositoryInterface $timeslotRepository
    )
    {
        parent::__construct($context);
        $this->filter = $filter;
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
        try {
            $collection = $this->filter->getCollection($this->timeslotRepository->getCollection());
            $collectionSize = $collection->count();
            foreach ($collection as $timeslot) {
                /** @var Timeslot $timeslot */
                $timeslot->setIsActive(0);
                $this->timeslotRepository->save($timeslot);
            }
            $this->messageManager->addSuccessMessage('A total of %1 record(s) have been disabled.', $collectionSize);
        } catch (LocalizedException $localizedException) {
            $this->messageManager->addErrorMessage($localizedException->getMessage());
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__("Some error occured while disabling the record(s)"));
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*');
    }
}