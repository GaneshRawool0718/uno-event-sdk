<?php

namespace Uno\EventSdk\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Uno\EventSdk\Subscriber\EventSubscriberRegistry;

class EventSubscriberPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(EventSubscriberRegistry::class)) {
            return;
        }

        $registry = $container->findDefinition(EventSubscriberRegistry::class);

        $taggedServices = $container->findTaggedServiceIds('uno_event_sdk.subscriber');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $tag) {
                $event = $tag['event'] ?? '*';
                $registry->addMethodCall('addSubscriber', [
                    $event,
                    new Reference($id)
                ]);
            }
        }
    }
}
