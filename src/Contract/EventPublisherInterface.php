<?php

namespace Uno\EventSdk\Contract;

use Uno\EventSdk\Exception\EventDispatchException;
use Uno\EventSdk\Message\EventMessage;

interface EventPublisherInterface
{
    /**
     * Publish an event to the underlying transport (Messenger).
     *
     * @param string $eventType
     * @param array  $payload
     * @param array  $metadata
     *
     * @return EventMessage
     *
     * @throws EventDispatchException When dispatch to messenger fails.
     */
    public function publish(string $eventType, array $payload, array $metadata = []): EventMessage;
}
