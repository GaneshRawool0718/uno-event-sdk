<?php

namespace Uno\EventSdk\Contract;

interface EventSubscriberInterface
{
    /**
     * Handle the incoming event.
     *
     * @param SdkEventSubscriberInterface $message
     *
     * @return void
     */
    public function handle(EventMessageInterface $message): void;
}
