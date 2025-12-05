<?php

namespace Uno\EventSdk\Subscriber;

use Uno\EventSdk\Contract\EventSubscriberInterface;

class EventSubscriberRegistry
{
    /** @var array<string, EventSubscriberInterface[]> */
    
    private array $subscribers = [];

    public function addSubscriber(string $event, EventSubscriberInterface $subscriber): void
    {
        $this->subscribers[$event][] = $subscriber;
    }

    public function getSubscribersFor(string $event): array
    {
        return array_merge(
            $this->subscribers['*'] ?? [],
            $this->subscribers[$event] ?? []
        );
    }
}
