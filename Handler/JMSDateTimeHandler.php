<?php


namespace Giacomoto\Bundle\GiacomotoDtoBundle\Handler;

use Giacomoto\Bundle\GiacomotoDtoBundle\Util\TimestampUtil;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Handler\SubscribingHandlerInterface;

class JMSDateTimeHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods(): array
    {
        return [
            [
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'DateTime',
                'method' => 'serializeDateTimeToJson',
            ],
            [
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'DateTimeImmutable',
                'method' => 'serializeDateTimeImmutableToJson',
            ],
//            [
//                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
//                'format' => 'json',
//                'type' => 'DateTimeImmutable',
//                'method' => 'deserializeDateTimeImmutableToJson',
//            ],
        ];
    }

    public function serializeDateTimeToJson(JsonSerializationVisitor $visitor, \DateTime $date, array $type, Context $context): string|int
    {
        if (strtolower($type['params'][0]) === 'timestamp') {
            return TimestampUtil::fromDateTimeToJsTimestamp($date);
        }
        return $date->format($type['params'][0]);
    }

    public function serializeDateTimeImmutableToJson(JsonSerializationVisitor $visitor, \DateTimeImmutable $date, array $type, Context $context): string|int
    {
        if (strtolower($type['params'][0]) === 'timestamp') {
            return TimestampUtil::fromDateTimeToJsTimestamp($date);
        }
        return $date->format($type['params'][0]);
    }
}
