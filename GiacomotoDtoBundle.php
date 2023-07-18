<?php

namespace Giacomoto\Bundle\GiacomotoDtoBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GiacomotoDtoBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
