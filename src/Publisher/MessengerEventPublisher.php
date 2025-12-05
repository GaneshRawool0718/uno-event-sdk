<?php

namespace Uno\EventSdk\Publisher;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Exception\TransportException;
use Uno\EventSdk\Contract\EventPublisherInterface;
use Uno\EventSdk\Message\EventMessage;
use Uno\EventSdk\Exception\EventDispatchException;

class MessengerEventPublisher implements EventPublisherInterface
{   
    /**
     * @var MessageBusInterface
     * @var array<string, mixed>
     * @var LoggerInterface|null
     * 
     */
    private $bus;
    private $defaultMetadata;
    private $logger;

    public function __construct(
        MessageBusInterface $bus,
        array $defaultMetadata = [],
        ?LoggerInterface $logger = null
    ) {
        $this->bus             = $bus;
        $this->defaultMetadata = $defaultMetadata;
        $this->logger          = $logger;
    }

    public function publish(string $eventType, array $payload, array $metadata = []): EventMessage
{
    $message = new EventMessage(
        $eventType,
        $payload,
        array_merge($this->defaultMetadata, $metadata)
    );

    try {
        $this->bus->dispatch($message);

        if ($this->logger) {
            $this->logger->info('Event dispatched', [
                'event_type' => $eventType,
                'metadata'   => $metadata
            ]);
        }

    } catch (TransportException $e) {
        if ($this->logger) {
            $this->logger->error('Transport error', ['error' => $e->getMessage()]);
        }
        throw new EventDispatchException($e->getMessage(), 0, $e);
    }

    return $message;
}

}
