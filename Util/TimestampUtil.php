<?php

namespace Giacomoto\Bundle\GiacomotoDtoBundle\Util;

final class TimestampUtil
{
    public static function fromDateTimeToJsTimestamp(\DateTime | \DateTimeImmutable $dateTime): int {
        return $dateTime->getTimestamp() * 1000;
    }

    public static function fromJsTimestampToDateTime(int $jsTimestamp): \DateTimeImmutable {
        return (new \DateTimeImmutable())->setTimestamp($jsTimestamp / 1000);
    }
}
