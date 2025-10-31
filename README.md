# WeChatWorkContactWayBundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/wechat-work-contact-way-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-work-contact-way-bundle)
[![PHP Version](https://img.shields.io/packagist/php-v/tourze/wechat-work-contact-way-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-work-contact-way-bundle)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/github/actions/workflow/status/tourze/wechat-work-contact-way-bundle/main.yml?branch=master&style=flat-square)](https://github.com/tourze/wechat-work-contact-way-bundle/actions)
[![Code Coverage](https://img.shields.io/codecov/c/github/tourze/wechat-work-contact-way-bundle/master.svg?style=flat-square)](https://codecov.io/github/tourze/wechat-work-contact-way-bundle)
[![Quality Score](https://img.shields.io/scrutinizer/g/tourze/wechat-work-contact-way-bundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/tourze/wechat-work-contact-way-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/wechat-work-contact-way-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-work-contact-way-bundle)

A comprehensive Symfony bundle for managing WeChat Work customer contact ways ("Contact Me" feature) with full API support, automated synchronization, and enterprise-grade reliability.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
  - [Entity](#entity)
  - [Commands](#commands)
  - [Requests](#requests)
  - [Repository](#repository)
  - [Event Listeners](#event-listeners)
- [Advanced Usage](#advanced-usage)
- [API Reference](#api-reference)
- [Best Practices](#best-practices)
- [Testing](#testing)
- [Requirements](#requirements)
- [Contributing](#contributing)
- [Security](#security)
- [License](#license)

## Features

- Synchronize contact ways from WeChat Work
- Manage contact way configurations
- Support temporary and permanent contact ways
- Batch operations for contact ways
- Automated synchronization via cron jobs

## Installation

Install the bundle via Composer:

```bash
composer require tourze/wechat-work-contact-way-bundle
```

## Configuration

Add the bundle to your `config/bundles.php`:

```php
return [
    // ...
    WechatWorkContactWayBundle\WechatWorkContactWayBundle::class => ['all' => true],
];
```

## Usage

### Entity

The bundle provides the `ContactWay` entity to store contact way information:

```php
use WechatWorkContactWayBundle\Entity\ContactWay;

// Create a new contact way
$contactWay = new ContactWay();
$contactWay->setConfigId('contact_way_config_id');
$contactWay->setType(1); // 1: single user, 2: multiple users
$contactWay->setScene(1); // 1: QR code in group chat, 2: QR code in enterprise
```

### Commands

#### Sync Contact Ways

Synchronize all contact ways from WeChat Work:

```bash
php bin/console wechat-work:sync-contact-ways
```

This command will:
- Fetch all contact ways from WeChat Work API
- Update existing contact ways or create new ones
- Automatically run daily at 6:01 AM via cron job

### Requests

The bundle includes several request classes for WeChat Work API:

- `AddContactWayRequest` - Add a new contact way
- `UpdateContactWayRequest` - Update an existing contact way
- `DeleteContactWayRequest` - Delete a contact way
- `GetContactWayRequest` - Get contact way details
- `ListContactWayRequest` - List all contact ways
- `CloseTempChatRequest` - Close temporary chat

### Repository

Use the `ContactWayRepository` to query contact ways:

```php
use WechatWorkContactWayBundle\Repository\ContactWayRepository;

// In your controller or service
public function __construct(private ContactWayRepository $contactWayRepository)
{
}

// Find by config ID
$contactWay = $this->contactWayRepository->findOneBy(['configId' => 'config_id']);

// Find all active contact ways
$contactWays = $this->contactWayRepository->findAll();
```

### Event Listeners

The bundle includes event listeners that automatically manage contact way data:

- `ContactWayListener` - Handles contact way entity lifecycle events
- Automatically updates contact way information when entities are persisted

## Advanced Usage

### Custom Contact Way Processing

For advanced scenarios, you can extend the synchronization process with 
custom processing logic:

```php
use WechatWorkContactWayBundle\Entity\ContactWay;
use WechatWorkContactWayBundle\Repository\ContactWayRepository;

class CustomContactWayProcessor
{
    public function __construct(private ContactWayRepository $repository)
    {
    }

    public function processContactWayData(array $data): ContactWay
    {
        $contactWay = $this->repository->findOneBy(['configId' => $data['config_id']]);
        
        if (!$contactWay) {
            $contactWay = new ContactWay();
            $contactWay->setConfigId($data['config_id']);
        }
        
        // Custom business logic here
        $this->applyCustomRules($contactWay, $data);
        
        return $contactWay;
    }
    
    private function applyCustomRules(ContactWay $contactWay, array $data): void
    {
        // Implement your custom business rules
        if ($data['scene'] === 1) {
            $contactWay->setRemark('Auto-generated QR code contact');
        }
    }
}
```

### Batch Operations

For processing multiple contact ways efficiently:

```php
use Doctrine\ORM\EntityManagerInterface;

class BatchContactWayProcessor
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function batchUpdate(array $contactWays): void
    {
        $batchSize = 20;
        $i = 0;
        
        foreach ($contactWays as $contactWay) {
            $this->em->persist($contactWay);
            
            if (($i % $batchSize) === 0) {
                $this->em->flush();
                $this->em->clear();
            }
            
            ++$i;
        }
        
        $this->em->flush();
        $this->em->clear();
    }
}
```

### Event-Driven Architecture

Listen to contact way events for custom integrations:

```php
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ContactWayEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postUpdate,
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof ContactWay) {
            // Handle new contact way creation
            $this->handleNewContactWay($entity);
        }
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof ContactWay) {
            // Handle contact way updates
            $this->handleContactWayUpdate($entity);
        }
    }
    
    private function handleNewContactWay(ContactWay $contactWay): void
    {
        // Custom logic for new contact ways
    }
    
    private function handleContactWayUpdate(ContactWay $contactWay): void
    {
        // Custom logic for contact way updates
    }
}
```

## Best Practices

### Performance

- Use repository queries instead of entity manager for complex queries
- Leverage the built-in synchronization command for regular updates
- Monitor API rate limits when making frequent requests

### Security

- Validate all input data before processing
- Use environment variables for sensitive configuration
- Implement proper error handling for API failures

### Error Handling

```php
try {
    $contactWay = $this->contactWayRepository->findOneBy(['configId' => $configId]);
    if (!$contactWay) {
        throw new \RuntimeException('Contact way not found');
    }
} catch (\Exception $e) {
    // Handle the error appropriately
    $this->logger->error('Contact way operation failed', ['error' => $e->getMessage()]);
}
```

## Testing

Run the test suite:

```bash
# Run all tests
vendor/bin/phpunit packages/wechat-work-contact-way-bundle/tests

# Run with coverage
vendor/bin/phpunit --coverage-html coverage packages/wechat-work-contact-way-bundle/tests

# Run PHPStan analysis
vendor/bin/phpstan analyse packages/wechat-work-contact-way-bundle
```

The bundle includes comprehensive tests covering:
- Unit tests for all request classes
- Integration tests for repository operations
- Command testing with various scenarios
- Entity validation and lifecycle events

## API Reference

### ContactWay Entity Properties

- `configId` - Unique configuration ID from WeChat Work
- `type` - Contact way type (1: single user, 2: multiple users)
- `scene` - Usage scene (1: QR code in group chat, 2: QR code in enterprise)
- `style` - Mini program widget style
- `remark` - Remark information
- `skipVerify` - Whether to skip verification when adding
- `state` - Channel parameter for tracking
- `qrCode` - QR code URL
- `user` - User IDs array
- `party` - Department IDs array
- `temp` - Whether it's a temporary session
- `expiresIn` - Temporary session QR code expiration time
- `chatExpiresIn` - Temporary session expiration time
- `unionId` - Temporary session union ID
- `conclusions` - Conclusion messages

## Requirements

- PHP 8.1 or higher
- Symfony 6.4 or higher
- Doctrine ORM 3.0 or higher

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email security@tourze.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.