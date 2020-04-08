<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Pwa\Controller\Adminhtml\Index;

use Codilar\Pwa\Api\PwaRepositoryInterface;
use Codilar\Pwa\Helper\DownloadHelper;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Export extends Action
{
    protected $resultPageFactory;
    /**
     * @var PwaRepositoryInterface
     */
    private $pwaRepository;
    /**
     * @var DownloadHelper
     */
    private $downloadHelper;

    /**
     * Export constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param PwaRepositoryInterface $pwaRepository
     * @param DownloadHelper $downloadHelper
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        PwaRepositoryInterface $pwaRepository,
        DownloadHelper $downloadHelper
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        $this->pwaRepository = $pwaRepository;
        $this->downloadHelper = $downloadHelper;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $collection = $this->pwaRepository->getCollection();
        $collection->getSelect()->reset(\Zend_Db_Select::COLUMNS);
        $collection->getSelect()->columns(['request_url', 'redirect_url']);
        $array = $collection->getData();
        $fileName = 'pwa_redirect_url_'.date("Y-m-d");
        $this->downloadHelper->outputCsv($array, $fileName);
    }
}