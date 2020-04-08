<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 30/11/18
 * Time: 3:21 PM
 */

namespace Codilar\CustomiseJewellery\Controller\Custom;

use Codilar\CustomiseJewellery\Model\CustomiseJewelleryFactory;
use Codilar\CustomiseJewellery\Model\ResourceModel\CustomiseJewellery;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Escaper;
use Magento\Framework\View\Result\PageFactory;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Index
 * @package Codilar\CustomiseJewellery\Controller\Custom
 */
class Index extends \Magento\Framework\App\Action\Action
{
    const REQUEST_TYPE1 = 'upload_any_design';
    const REQUEST_TYPE2 = 'customize_existing_design';
    const REQUEST_TYPE3 = 'work_with_our_designer';
    const SEND_EMAIL_TRUE = 1;
    const EMAIL_SENT_FALSE = 0;
    /**
     * @var PageFactory
     */
    protected $_pageFactory;
    /**
     * @var CustomiseJewelleryFactory
     */
    protected $customiseJewelleryFactory;
    /**
     * @var CustomiseJewellery
     */
    protected $customiseJewelleryResource;
    /**
     * @var UploaderFactory
     */
    protected $uploaderFactory;
    /**
     * @var AdapterFactory
     */
    protected $adapterFactory;
    /**
     * @var Filesystem
     */
    protected $filesystem;
    /**
     * @var Validator
     */
    private $validator;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var StateInterface
     */
    protected $inlineTranslation;
    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param CustomiseJewelleryFactory $customiseJewelleryFactory
     * @param CustomiseJewellery $customiseJewelleryResource
     * @param Session $customerSession
     * @param UploaderFactory $uploaderFactory
     * @param AdapterFactory $adapterFactory
     * @param Filesystem $filesystem
     * @param Validator $validator
     * @param Escaper $escaper
     * @param LoggerInterface $logger
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        CustomiseJewelleryFactory $customiseJewelleryFactory,
        CustomiseJewellery $customiseJewelleryResource,
        Session $customerSession,
        UploaderFactory $uploaderFactory,
        AdapterFactory $adapterFactory,
        Filesystem $filesystem,
        Validator $validator,
        Escaper $escaper,
        LoggerInterface $logger,
        StoreManagerInterface $storeManager

    )
    {
        $this->_pageFactory = $pageFactory;
        $this->_resultFactory = $context->getResultFactory();
        $this->customiseJewelleryFactory = $customiseJewelleryFactory;
        $this->customiseJewelleryResource = $customiseJewelleryResource;
        $this->customerSession = $customerSession;
        $this->uploaderFactory = $uploaderFactory;
        $this->adapterFactory = $adapterFactory;
        $this->filesystem = $filesystem;
        $this->validator = $validator;
        $this->escaper = $escaper;
        $this->logger = $logger;
        $this->_storeManager = $storeManager;
        return parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$this->validator->validate($this->getRequest())) {
            $this->messageManager->addErrorMessage('Invalid Formkey.');
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect->setPath('jewel/index/index');
            return $resultRedirect;
        }

        $post = (array)$this->getRequest()->getPost();

        $customiseJewelleryFactory = $this->customiseJewelleryFactory->Create();

        if ($post) {

            $type = $post['type'];
            $data['name'] = $post['name'];
            $data['mobile_number'] = $post['mobile'];
            $data['email'] = $post['email'];
            $data['details'] = $post['details'];
            $data['status'] = '0';
            if ($this->customerSession->isLoggedIn()) {
                $data['customer_id'] = $this->customerSession->getCustomer()->getId();
            } else {
                $data['session_id'] = $this->customerSession->getSessionId();

            }

            if ($type == 1) {
                $data['request_type'] = self::REQUEST_TYPE1;
                $data['budget'] = $post['budget'];
                try {

                    $uploaderFactory = $this->uploaderFactory->create(['fileId' => 'image']);
                    $uploaderFactory->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $imageAdapter = $this->adapterFactory->create();
                    /** start of validated image */
                    $uploaderFactory->addValidateCallback('custom_image_upload',
                        $imageAdapter, 'validateUploadFile');
                    $uploaderFactory->setAllowRenameFiles(true);
                    $uploaderFactory->setFilesDispersion(true);
                    $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
                    $destinationPath = $mediaDirectory->getAbsolutePath('custom_image');
                    $result = $uploaderFactory->save($destinationPath);
                    if (!$result) {
                        throw new LocalizedException(
                            __('File cannot be saved to path: $1', $destinationPath)
                        );
                    }
                    $imagepath = $result['file'];
                    $data['image_path'] = $imagepath;
                } catch (\Exception $e) {
                    $this->logger->critical($e->getMessage());
                }


            }
            if ($type == 2) {
                $data['request_type'] = self::REQUEST_TYPE2;
                $data['budget'] = $post['budget'];
                $data['product_sku'] = $post['jewel_sku'];
                $checkSkuId = $this->customerSession->getCheckProductId();
                $checkSkuStatus = $this->customerSession->getCheckSkuStatus();
                if ($checkSkuId != $post['jewel_sku']) {
                    $this->messageManager->addErrorMessage('Sku is Incorrect.');
                    /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
                    $resultRedirect->setPath('jewel/index/index');
                    return $resultRedirect;
                }
                if ($checkSkuStatus == "false") {
                    $this->messageManager->addErrorMessage('Enter Valid Sku.');
                    /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
                    $resultRedirect->setPath('jewel/index/index');
                    return $resultRedirect;
                }
            }
            if ($type == 3) {
                $data['request_type'] = self::REQUEST_TYPE3;
            }
            $data['send_email'] = self::SEND_EMAIL_TRUE;
            $data['email_sent'] = self::EMAIL_SENT_FALSE;
            $customiseJewelleryFactory->setData($data);
            $this->customiseJewelleryResource->Save($customiseJewelleryFactory);
            $this->messageManager->addSuccessMessage(__('Thanks for contacting us with your comments and questions. We will respond to you very soon.'));
            $resultRedirect = $this->_resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl('/jewel/index/index');
            return $resultRedirect;
        }

    }

}