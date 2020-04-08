<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Controller\Adminhtml\Index;


use Codilar\Carousel\Api\CarouselRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Ui\Component\MassAction\Filter;
use Codilar\Carousel\Model\ResourceModel\Carousel\CollectionFactory;


class MassDelete extends Action
{
    /**
     * @var Filter
     */
    private $filter;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var CarouselRepositoryInterface
     */
    private $carouselRepository;

    /**
     * MassDelete constructor.
     * @param Action\Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param CarouselRepositoryInterface $carouselRepository
     */
    public function __construct(
        Action\Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        CarouselRepositoryInterface $carouselRepository
    )
    {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->carouselRepository = $carouselRepository;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        foreach ($collection as $item) {
            $this->carouselRepository->delete($item);
        }
        $this->messageManager->addSuccessMessage(__("A total of %1 record(s) were deleted", count($collection)));
        return $this->resultRedirectFactory->create()->setPath('*/*');
    }
}