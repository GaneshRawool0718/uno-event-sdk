<?php

namespace Uno\EventSdk\Message;

use DateTimeImmutable;
use Uno\EventSdk\Contract\EventMessageInterface;
use Uno\EventSdk\Exception\InvalidEventException;

class EventMessage implements EventMessageInterface
{
    private string $eventType;
    private array $payload;
    private array $metadata;
    private DateTimeImmutable $createdAt;

    /**
     * @param string $eventType
     * @param array  $payload
     * @param array  $metadata
     *
     * @throws InvalidEventException
     */

    public function __construct(string $eventType, array $payload, array $metadata = [])
    {
        if ($eventType === '') {
            throw new InvalidEventException('Event type cannot be empty.');
        }

        $this->eventType = $eventType;
        $this->payload   = $payload;
        $this->metadata  = $metadata;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getEventType(): string { return $this->eventType; }
    public function getPayload(): array { return $this->payload; }
    public function getMetadata(): array { return $this->metadata; }
    public function getCreatedAt(): DateTimeImmutable { return $this->createdAt; }

    public function toArray(): array
    {
        return [
            'event_type' => $this->eventType,
            'payload' => $this->payload,
            'metadata' => $this->metadata,
            'created_at' => $this->createdAt->format(DATE_ATOM)
        ];
    }
}
