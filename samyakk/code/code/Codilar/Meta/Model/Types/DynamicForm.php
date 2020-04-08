<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Meta\Model\Types;


use Codilar\Meta\Api\Data\MetaData\MetaInterface;
use Codilar\Meta\Api\Data\MetaDataInterface;
use Codilar\Meta\Api\Data\MetaDataInterfaceFactory;
use Codilar\Meta\Api\MetaManagementInterface;
use Codilar\Meta\Api\MetaManagementInterfaceFactory;
use Codilar\Meta\Api\Types\MetaDataTypeInterface;
use Codilar\DynamicForm\Api\FormRepositoryInterface;
use Magento\Theme\Block\Html\Header\Logo;

class DynamicForm implements MetaDataTypeInterface
{
    /**
     * @var MetaManagementInterfaceFactory
     */
    private $metaManagementInterfaceFactory;
    /**
     * @var MetaDataInterfaceFactory
     */
    private $metaDataInterfaceFactory;
    /**
     * @var Logo
     */
    private $logo;
    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;

    /**
     * Product constructor.
     * @param FormRepositoryInterface $formRepository
     * @param MetaManagementInterfaceFactory $metaManagementInterfaceFactory
     * @param MetaDataInterfaceFactory $metaDataInterfaceFactory
     * @param Logo $logo
     */
    public function __construct(
        FormRepositoryInterface $formRepository,
        MetaManagementInterfaceFactory $metaManagementInterfaceFactory,
        MetaDataInterfaceFactory $metaDataInterfaceFactory,
        Logo $logo
    )
    {
        $this->metaManagementInterfaceFactory = $metaManagementInterfaceFactory;
        $this->metaDataInterfaceFactory = $metaDataInterfaceFactory;
        $this->logo = $logo;
        $this->formRepository = $formRepository;
    }

    /**
     * @param string $id
     * @return \Codilar\Meta\Api\Data\MetaDataInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMetaTypeData($id)
    {
        /** @var MetaDataInterface $data */
        $data = $this->metaDataInterfaceFactory->create();
        $form = $this->formRepository->load($id, 'identifier');
        $data->setTitle($form->getTitle());
        $data->setMeta($this->getMetaData($form));
        return $data;
    }

    /**
     * @param $form
     * @return \Codilar\Meta\Api\Data\MetaData\MetaInterface[]
     */
    public function getMetaData($form) {
        $metaDataArray = [];
        /** @var MetaManagementInterface $meta */
        $meta = $this->metaManagementInterfaceFactory->create();
        $formTitle = $form->getTitle();
        $metaDataArray[] = $meta->getMetaData("description", $formTitle);
        $metaDataArray[] = $meta->getMetaData("keywords", $formTitle);

        $image = $this->logo->getLogoSrc();
        $metaDataArray[] = $meta->getMetaData("og:image", $image);
        $metaDataArray[] = $meta->getMetaData("og:image:secure_url", $image);

        $metaDataArray[] = $meta->getMetaData("og:type", 'dynamic_form');
        $metaDataArray[] = $meta->getMetaData("og:title", $formTitle);
        return $metaDataArray;
    }
}