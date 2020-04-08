<?php
namespace Codilar\Customer\Ui\Component\Listing\Column;

use Codilar\Customer\Helper\Data as CustomerHelper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class PhoneNumber
 * @package Codilar\Customer\Ui\Component\Listing\Column
 */
class PhoneNumber extends Column
{
    /**
     *
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array              $components
     * @param array              $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if($item['otp_verified'] == 1) {
                    $item['otp_verified'] = CustomerHelper::VERIFIED_LABEL;
                } else {
                    $item['otp_verified'] = CustomerHelper::NOT_VERIFIED_LABEL;
                }
            }
        }
        return $dataSource;
    }
}