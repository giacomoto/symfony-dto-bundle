<?php

namespace Giacomoto\Bundle\GiacomotoDtoBundle\Exception;

use RuntimeException;

class DtoUnexpectedTypeException extends RuntimeException {
    private const CODE = 113;

    public function __construct(string $message)
    {
        parent::__construct($message, self::CODE);
    }
}
