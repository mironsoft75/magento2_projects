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
use Codilar\Timeslot\Model\ResourceModel\Timeslot as TimeslotResource;
use Codilar\Timeslot\Api\TimeslotRepositoryInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class inlineEdit extends Action
{
    /**
     * @var TimeslotRepositoryInterface
     */
    private $timeslotRepository;

    /**
     * inlineEdit constructor.
     * @param Action\Context $context
     * @param TimeslotRepositoryInterface $timeslotRepository
     */
    public function __construct(
        Action\Context $context,
        TimeslotRepositoryInterface $timeslotRepository
    )
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
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' =>true,
            ]);
        }

        $idField = TimeslotResource::ID_FIELD;
        try {
            foreach ($postItems as $postItem) {
                $model = $this->timeslotRepository->load($postItem[$idField]);
                $model->addData($postItem);
                $this->timeslotRepository->save($model);
            }
            $data = [
                'error' => false,
                'messages' => [__("Timeslot saved successfully")]
            ];
        } catch (LocalizedException $localizedException) {
            $data = [
                'error' => true,
                'messages' => [$localizedException->getMessage()]
            ];
        } catch (\Exception $exception) {
            $data = [
                'error' => true,
                'messages' => [__("Some error occured while saving the timeslot. Please try again later")]
            ];
        }

        return $resultJson->setData($data);
    }
}