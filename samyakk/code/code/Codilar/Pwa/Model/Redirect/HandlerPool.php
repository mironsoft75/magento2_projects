<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Pwa\Model\Redirect;


class HandlerPool
{
    /**
     * @var HandlerInterface[]
     */
    private $handlers;

    /**
     * HandlerPool constructor.
     * @param array $handlers
     */
    public function __construct(
        array $handlers = []
    )
    {
        $this->handlers = $handlers;
    }

    /**
     * @return HandlerInterface[]
     */
    public function getHandlers()
    {
        return $this->handlers;
    }
}