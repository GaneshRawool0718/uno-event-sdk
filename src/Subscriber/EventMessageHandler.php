<?php

namespace Uno\EventSdk\Subscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Uno\EventSdk\Message\EventMessage;

class EventMessageHandler implements MessageHandlerInterface
{
    /** @var EventSubscriberRegistry */
    private $registry;

    /** @var LoggerInterface|null */
    private $logger;

    public function __construct(
        EventSubscriberRegistry $registry,
        ?LoggerInterface $logger = null
    ) {
        $this->registry = $registry;
        $this->logger   = $logger;
    }

    public function __invoke(EventMessage $message): void
    {
        $subscribers = $this->registry->getSubscribersFor($message->getEventType());

        foreach ($subscribers as $subscriber) {

            try {
                $subscriber->handle($message);

            } catch (\Throwable $e) {
                // VERY IMPORTANT: Log but DO NOT throw
                // Retry must be controlled *inside* the subscriber
                if ($this->logger) {
                    $this->logger->error('Subscriber failed', [
                        'subscriber' => get_class($subscriber),
                        'error'      => $e->getMessage(),
                    ]);
                }

                throw $e;
                
            }
        }
    }
}
