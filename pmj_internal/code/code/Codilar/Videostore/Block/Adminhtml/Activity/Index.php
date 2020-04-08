<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 25/11/18
 * Time: 7:19 PM
 */

namespace Codilar\Videostore\Block\Adminhtml\Activity;

use Codilar\Videostore\Model\ResourceModel\VideostoreRequestActivity\Collection;
use Magento\Framework\View\Element\Template;


class Index extends Template
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     * Index constructor.
     * @param Template\Context $context
     * @param Collection $collection
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Collection $collection,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->collection = $collection;
    }

    public function getActivityDetails()
    {
        return $this->collection->getData();
    }

}