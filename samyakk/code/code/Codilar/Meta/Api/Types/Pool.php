<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Meta\Api\Types;


use Codilar\Migration\Model\Migration\Types\MigrationTypeInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Pool
{
    /**
     * @var MetaDataTypeInterface[]
     */
    private $metaTypes;

    /**
     * Pool constructor.
     * @param array $metaTypes
     */
    public function __construct(
        array $metaTypes = []
    )
    {
        $this->metaTypes = $metaTypes;
    }

    /**
     * @param string $type
     * @return MetaDataTypeInterface
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getTypeInstance($type)
    {
        if (!array_key_exists($type, $this->metaTypes)) {
            throw NoSuchEntityException::singleField('type', $type);
        } else if (!$this->metaTypes[$type] instanceof MetaDataTypeInterface) {
            throw new LocalizedException(__("Meta type %1 must implement interface %2", $type, MigrationTypeInterface::class));
        }
        return $this->metaTypes[$type];
    }
}