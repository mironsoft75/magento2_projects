<?php
namespace Codilar\Videostore\Model\VideoRequest\Status;

class Options implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var null|array
     */
    protected $options;

    /**
     * @return array|null
     */
    public function toOptionArray()
    {
        return [
            ['label' => __('Pending'), 'value' => 'Pending'],
            ['label' => __('Approve'), 'value' => 'Approved'],
            ['label' => __('Reject'), 'value' => 'Rejected'],
            ['label' => __('Processing'), 'value' => 'Processing'],
        ];
    }
}
