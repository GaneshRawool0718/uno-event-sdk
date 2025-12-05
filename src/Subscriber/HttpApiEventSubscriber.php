<?php

namespace Uno\EventSdk\Subscriber;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Uno\EventSdk\Contract\EventSubscriberInterface;
use Uno\EventSdk\Contract\EventMessageInterface;

class HttpApiEventSubscriber implements EventSubscriberInterface
{
    /** @var HttpClientInterface */
    private $client;

    /** @var string */
    private $logsUrl;

    /** @var float */
    private $timeout;

    /** @var LoggerInterface|null */
    private $logger;

    public function __construct(
        HttpClientInterface $client,
        string $logsUrl,
        float $timeout = 0.5,                 // optimized timeout
        ?LoggerInterface $logger = null        // nullable - clean, PHP 7.4-safe
    ) {
        $this->client  = $client;
        $this->logsUrl = $logsUrl;
        $this->timeout = $timeout;
        $this->logger  = $logger;
    }

    public function handle(EventMessageInterface $message): void
    {
        try {
            // Do NOT modify the payload
            $this->client->request('POST', $this->logsUrl, [
                'json'    => $message->getPayload(),
                'timeout' => $this->timeout
            ]);

            if ($this->logger) {
                $this->logger->info('Event successfully delivered to audit-log-service', [
                    'url' => $this->logsUrl,
                    'event_type' => $message->getEventType()
                ]);
            }

        } catch (TransportExceptionInterface $e) {

            // Network timeout / audit-log-service unreachable
            if ($this->logger) {
                $this->logger->warning('Audit service unreachable, Messenger will retry', [
                    'url'   => $this->logsUrl,
                    'error' => $e->getMessage(),
                ]);
            }

            // CRITICAL: Throw → Messenger RETRIES this event
            throw $e;

        } catch (\Throwable $e) {

            // Unexpected errors (serialization, symfony http client, 500 errors etc.)
            if ($this->logger) {
                $this->logger->error('Unexpected delivery failure. Messenger will retry.', [
                    'url'   => $this->logsUrl,
                    'error' => $e->getMessage(),
                ]);
            }

            throw $e; // REQUIRED for retry
        }
    }
}
