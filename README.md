# Event SDK (Symfony Bundle)

A reusable and lightweight Event SDK for Symfony applications that simplifies event publishing and event subscribing across microservices.

This SDK is built using Symfony Messenger, supports Redis transport, and provides a clean, unified structure for asynchronous communication.

---

## Features

- Publish events with a standard EventMessage format
- Subscribe to events using Symfony Messenger workers
- Extendable subscriber system using a registry pattern
- Optional HTTP forwarding subscriber (e.g., send events to any service like Spring Boot)
- Automatic dependency injection configuration
- Compatible with Symfony 4.4 → 7.x
- Can be used via:
  - Local path
  - GitHub repository
  - Composer (Packagist)

---

## Installation

### 1. Install via Composer (Packagist)
```bash
composer require uno/event-sdk
```

### 2. Install from GitHub (without Packagist)
```bash
composer require uno/event-sdk:dev-main
```

### 3. Use Locally (development mode)

In your main project `composer.json`:
```json
{
  "repositories": [
    {
      "type": "path",
      "url": "../uno-event-sdk"
    }
  ]
}
```

Then:
```bash
composer require uno/event-sdk:* --dev
```

---

## Configuration

Enable the bundle automatically (Flex) or in `config/bundles.php`:
```php
return [
    Uno\EventSdk\UnoEventSdkBundle::class => ['all' => true],
];
```

Configure the messenger transport (Redis example):
```yaml
framework:
  messenger:
    transports:
      async: "%env(MESSENGER_TRANSPORT_DSN)%"
```

Example DSN:
```
redis://localhost:6379/messages
```

---

## Publishing an Event
```php
$publisher->publish('user.registered', [
    'id' => 1,
    'email' => 'test@example.com'
]);
```

---

## Subscribing to Events

Run worker:
```bash
php bin/console messenger:consume async -vv
```

---

## Project Structure
```
src/
├── Contract/
├── Publisher/
├── Subscriber/
├── Message/
├── Exception/
├── EventListener/
├── DependencyInjection/
├── Resources/config/services.yaml
└── UnoEventSdkBundle.php
```