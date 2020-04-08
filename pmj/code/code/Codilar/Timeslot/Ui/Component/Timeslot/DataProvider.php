<?php
/**
 * @package     eat
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Timeslot\Ui\Component\Timeslot;

use Codilar\Timeslot\Api\TimeslotRepositoryInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        TimeslotRepositoryInterface $timeslotRepository,
        array $meta = [],
        array $data = [])
    {
        $this->collection = $timeslotRepository->getCollection();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

}