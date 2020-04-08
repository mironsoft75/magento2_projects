<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Controller\Form;


use Codilar\DynamicForm\Api\Data\Form\ElementInterface;
use Codilar\DynamicForm\Api\Data\FormInterface;
use Codilar\DynamicForm\Api\Form\ResponseRepositoryInterface;
use Codilar\DynamicForm\Api\FormRepositoryInterface;
use Codilar\DynamicForm\Exception\IllegalEntryException;
use Codilar\DynamicForm\Helper\VariableFieldRenderer;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Codilar\DynamicForm\Api\Data\Form\Response\FieldInterface;
use Codilar\DynamicForm\Api\Data\Form\Response\FieldInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\App\Filesystem\DirectoryList;

class Submit extends Action
{
    /**
     * @var ResponseRepositoryInterface
     */
    private $responseRepository;
    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;
    /**
     * @var FieldInterfaceFactory
     */
    private $fieldInterfaceFactory;
    /**
     * @var CustomerSession
     */
    private $customerSession;
    /**
     * @var RemoteAddress
     */
    private $remoteAddress;
    /**
     * @var VariableFieldRenderer
     */
    private $variableFieldRenderer;
    /**
     * @var Filesystem
     */
    private $fileSystem;
    /**
     * @var UploaderFactory
     */
    private $uploaderFactory;

    /**
     * Submit constructor.
     * @param Context $context
     * @param ResponseRepositoryInterface $responseRepository
     * @param FormRepositoryInterface $formRepository
     * @param FieldInterfaceFactory $fieldInterfaceFactory
     * @param CustomerSession $customerSession
     * @param RemoteAddress $remoteAddress
     * @param VariableFieldRenderer $variableFieldRenderer
     * @param Filesystem $fileSystem
     * @param UploaderFactory $uploaderFactory
     */
    public function __construct(
        Context $context,
        ResponseRepositoryInterface $responseRepository,
        FormRepositoryInterface $formRepository,
        FieldInterfaceFactory $fieldInterfaceFactory,
        CustomerSession $customerSession,
        RemoteAddress $remoteAddress,
        VariableFieldRenderer $variableFieldRenderer,
        Filesystem $fileSystem,
        UploaderFactory $uploaderFactory
    )
    {
        parent::__construct($context);
        $this->responseRepository = $responseRepository;
        $this->formRepository = $formRepository;
        $this->fieldInterfaceFactory = $fieldInterfaceFactory;
        $this->customerSession = $customerSession;
        $this->remoteAddress = $remoteAddress;
        $this->variableFieldRenderer = $variableFieldRenderer;
        $this->fileSystem = $fileSystem;
        $this->uploaderFactory = $uploaderFactory;
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
            $form = $this->formRepository->load($this->getRequest()->getParam('id'));
            $data = $this->getRequest()->getPost()->toArray();
            $fileData = $_FILES;
            $data = array_merge($data, $fileData);
            $response = $this->responseRepository->create();
            $customerEmail = $this->customerSession->isLoggedIn() ? $this->customerSession->getCustomer()->getEmail() : '';
            $response->setFormId($form->getId())
                ->setFields([])
                ->setCustomerEmail($customerEmail)
                ->setCustomerIp($this->remoteAddress->getRemoteAddress());
            $response = $this->responseRepository->save($response, false);
            $fields = [];
            foreach ($form->getFormElements() as $formElement) {
                if (array_key_exists($formElement->getName(), $data)) {
                    /** @var FieldInterface $field */
                    $field = $this->fieldInterfaceFactory->create();
                    $field->setType($formElement->getType())
                        ->setName($formElement->getName())
                        ->setValue($this->prepareFormResponseValue($response, $formElement, $data[$formElement->getName()]));
                    $fields[] = $field;
                }
            }
            $response->setFields($fields);
            $this->responseRepository->save($response);

            $this->messageManager->addSuccessMessage(__($this->variableFieldRenderer->render($form->getResponseMessage(), $data)));
        } catch (IllegalEntryException $illegalEntryException) {
            $this->messageManager->addErrorMessage($illegalEntryException->getMessage());
        } catch (NoSuchEntityException $e) {
            $this->messageManager->addErrorMessage(__("Form no longer exists"));
        } catch (CouldNotSaveException $couldNotSaveException) {
            $this->messageManager->addErrorMessage(__("Could not submit form. Please try again"));
        }
        return $this->resultRedirectFactory->create()->setRefererOrBaseUrl();
    }

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\ResponseInterface $response
     * @param ElementInterface $element
     * @param string $value
     * @return string
     * @throws IllegalEntryException
     */
    protected function prepareFormResponseValue($response, $element, $value)
    {
        if ($element->getType() == ElementInterface::TYPE_FILE) {
            $value = $this->uploadFileAndGetPath(
                $element->getName(),
                $response->getId() . '/' . $this->sanitizeDirectoryName($element->getName())
            );
        }
        return $value;
    }

    /**
     * @param string $fileId
     * @param string $destinationPath
     * @return string
     * @throws IllegalEntryException
     */
    protected function uploadFileAndGetPath($fileId, $destinationPath)
    {
        $suffix = 'dynamic_form' . DIRECTORY_SEPARATOR . $destinationPath;
        $destinationPath = $this->getDestinationPath($suffix);
        try {
            $uploader = $this->uploaderFactory->create(['fileId' => $fileId])
                ->setAllowCreateFolders(true)
                ->setAllowedExtensions();
            $destinationFile = $uploader->save($destinationPath);
            if (!$destinationFile) {
                throw new IllegalEntryException(
                    __('File cannot be saved to path: $1', $destinationPath)
                );
            }
            $value = $suffix . DIRECTORY_SEPARATOR . $destinationFile['file'];
        } catch (\Exception $e) {
            throw new IllegalEntryException(__("Error uploading file. Please try again"));
        }
        return $value;
    }

    public function getDestinationPath($suffix = '')
    {
        try {
            return $this->fileSystem
                ->getDirectoryWrite(DirectoryList::VAR_DIR)
                ->getAbsolutePath($suffix);
        } catch (FileSystemException $e) {
            $this->messageManager->addError(
                __($e->getMessage())
            );
        }
    }

    protected function sanitizeDirectoryName($file)
    {
        // Remove anything which isn't a word, whitespace, number
        // or any of the following caracters -_~,;[]().
        // If you don't need to handle multi-byte characters
        // you can use preg_replace rather than mb_ereg_replace
        $file = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $file);
        $file = mb_ereg_replace("([\.]{2,})", '', $file);
        return $file;
    }
}