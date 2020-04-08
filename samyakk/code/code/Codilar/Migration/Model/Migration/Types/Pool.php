<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Migration\Model\Migration\Types;


use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Pool
{
    /**
     * @var MigrationTypeInterface[]
     */
    private $migrationTypes;

    /**
     * Pool constructor.
     * @param array $migrationTypes
     */
    public function __construct(
        array $migrationTypes = []
    )
    {
        $this->migrationTypes = $migrationTypes;
    }

    /**
     * @param string $type
     * @return MigrationTypeInterface
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getTypeInstance($type)
    {
        if (!array_key_exists($type, $this->migrationTypes)) {
            throw NoSuchEntityException::singleField('type', $type);
        } else if (!$this->migrationTypes[$type] instanceof MigrationTypeInterface) {
            throw new LocalizedException(__("Migration type %1 must implement interface %2", $type, MigrationTypeInterface::class));
        }
        return $this->migrationTypes[$type];
    }
}