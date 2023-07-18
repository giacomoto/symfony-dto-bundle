<?php

namespace Giacomoto\Bundle\GiacomotoDtoBundle\Service;

use JMS\Serializer\Serializer;
use Giacomoto\Bundle\GiacomotoDtoBundle\Handler\JMSDateTimeHandler;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Expression\ExpressionEvaluator;
use JMS\Serializer\Accessor\DefaultAccessorStrategy;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use JMS\SerializerBundle\ExpressionLanguage\BasicSerializerFunctionsProvider;

class JMSSerializerService
{
    protected Serializer $serializer;
    protected SerializationContext $context;

    public function __construct()
    {
        $this->context = SerializationContext::create()->setSerializeNull(true);
        $this->serializer = SerializerBuilder::create()
            ->configureHandlers(function (HandlerRegistry $registry) {
                // convert dates with custom type "timestamp" in timestamp
                $registry->registerSubscribingHandler(new JMSDateTimeHandler());
            })
            // serialize properties in camelcase
            ->setPropertyNamingStrategy(new IdenticalPropertyNamingStrategy())
            // needed for virtual properties
            ->setAccessorStrategy($this->getDefaultAccessorStrategy())
            ->build();
    }

    public function setGroups(array $groups): self
    {
        $this->context->setGroups($groups);
        return $this;
    }

    public function serialize(mixed $data, $format = 'json'): string
    {
        return $this->serializer->serialize(
            $data,
            $format,
            $this->context,
        );
    }

    private function getDefaultAccessorStrategy(): DefaultAccessorStrategy
    {
        $expressionLanguage = new ExpressionLanguage();
        $expressionLanguage->registerProvider(new BasicSerializerFunctionsProvider());
        $expressionEvaluator = new ExpressionEvaluator($expressionLanguage);

        return new DefaultAccessorStrategy($expressionEvaluator);
    }
}
