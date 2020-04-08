<?php
namespace Codilar\Gst\Block\Customer;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Customer\Model\Session;
class AddressEdit extends Template
{
    /**
     * @var AddressInterface
     */
    private $address;
    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepository;
    /**
     * @var $addressInterfaceFactory
     */
    private $addressFactory;
    /**
     * @var $customerSession
     */
    private $customerSession;

    /**
     * AddressEdit constructor.
     * @param Template\Context $context
     * @param AddressInterfaceFactory $addressInterfaceFactory
     * @param AddressRepositoryInterface $addressRepository
     * @param Session $customerSession
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        AddressInterfaceFactory $addressInterfaceFactory,
        AddressRepositoryInterface $addressRepository,
        Session $customerSession,
        array $data = [])
    {
        $this->addressFactory = $addressInterfaceFactory;
        $this->addressRepository = $addressRepository;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        $addressId = $this->getRequest()->getParam('id');
        if($addressId){
            try{
                $this->address = $this->addressRepository->getById($addressId);
                if($this->address->getCustomerId() != $this->customerSession->getCustomerId()) {
                    $this->address = null;
                }
            }catch (NoSuchEntityException $exception){
                $this->address = null;
            }

        }

        if(null === $this->address) {
            $this->address = $this->addressFactory->create();
        }

        return parent::_prepareLayout(); // TODO: Change the autogenerated stub
    }

    protected function _toHtml()
    {
        $customWidgetBlock = $this->getLayout()->createBlock('Codilar\Gst\Block\Customer\Widget\Custom');
        $customWidgetBlock->setAddress($this->address);
        return $customWidgetBlock->toHtml(); // TODO: Change the autogenerated stub
    }
}