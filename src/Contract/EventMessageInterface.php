<?php

namespace Uno\EventSdk\Contract;

interface EventMessageInterface
{
    /**
     * Logical event type/name, e.g. "user.registered".
     *
     * @return string
     */
    public function getEventType(): string;

    /**
     * Main payload/body of the event.
     *
     * @return array
     */
    public function getPayload(): array;

    /**
     * Additional metadata (trace ID, source, correlation, etc.).
     *
     * @return array
     */
    public function getMetadata(): array;

    /**
     * Export event as a plain array (useful for logs or JSON).
     *
     * @return array
     */
    public function toArray(): array;
}
