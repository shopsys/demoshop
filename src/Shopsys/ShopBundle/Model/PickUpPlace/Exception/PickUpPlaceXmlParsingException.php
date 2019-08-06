<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\PickUpPlace\Exception;

use Exception;

class PickUpPlaceXmlParsingException extends Exception implements PickUpPlaceExceptionInterface
{
    /**
     * @param string $message
     * @param \Exception|null $previous
     */
    public function __construct($message, ?Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
