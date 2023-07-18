<?php

namespace Giacomoto\Bundle\GiacomotoDtoBundle\Transformer;

use Giacomoto\Bundle\GiacomotoDtoBundle\Interface\IDtoTransformer;

abstract class DtoTransformer implements IDtoTransformer
{
    public function transformFromObjects(iterable $entities): iterable
    {
        $dto = [];

        foreach ($entities as $entity) {
            $dto[] = $this->transformFromObject($entity);
        }

        return $dto;
    }
}
