# WeChatWorkContactWayBundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/wechat-work-contact-way-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-work-contact-way-bundle)
[![PHP Version](https://img.shields.io/packagist/php-v/tourze/wechat-work-contact-way-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-work-contact-way-bundle)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/github/actions/workflow/status/tourze/wechat-work-contact-way-bundle/main.yml?branch=master&style=flat-square)](https://github.com/tourze/wechat-work-contact-way-bundle/actions)
[![Code Coverage](https://img.shields.io/codecov/c/github/tourze/wechat-work-contact-way-bundle/master.svg?style=flat-square)](https://codecov.io/github/tourze/wechat-work-contact-way-bundle)
[![Quality Score](https://img.shields.io/scrutinizer/g/tourze/wechat-work-contact-way-bundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/tourze/wechat-work-contact-way-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/wechat-work-contact-way-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-work-contact-way-bundle)

一个全面的企业微信客户联系「联系我」功能 Symfony Bundle，提供完整的 API 支持、自动同步和企业级可靠性。

## 目录

- [功能特性](#功能特性)
- [安装](#安装)
- [配置](#配置)
- [使用方法](#使用方法)
  - [实体](#实体)
  - [命令](#命令)
  - [请求类](#请求类)
  - [仓储](#仓储)
  - [事件监听器](#事件监听器)
- [高级用法](#高级用法)
- [API 参考](#api-参考)
- [最佳实践](#最佳实践)
- [测试](#测试)
- [系统要求](#系统要求)
- [贡献](#贡献)
- [安全](#安全)
- [许可证](#许可证)

## 功能特性

- 同步企业微信联系我配置
- 管理联系我方式配置
- 支持临时和永久联系方式
- 批量操作联系方式
- 通过定时任务自动同步

## 安装

通过 Composer 安装：

```bash
composer require tourze/wechat-work-contact-way-bundle
```

## 配置

在 `config/bundles.php` 中添加 Bundle：

```php
return [
    // ...
    WechatWorkContactWayBundle\WechatWorkContactWayBundle::class => ['all' => true],
];
```

## 使用方法

### 实体

Bundle 提供了 `ContactWay` 实体来存储联系方式信息：

```php
use WechatWorkContactWayBundle\Entity\ContactWay;

// 创建新的联系方式
$contactWay = new ContactWay();
$contactWay->setConfigId('contact_way_config_id');
$contactWay->setType(1); // 1: 单人, 2: 多人
$contactWay->setScene(1); // 1: 群聊中的二维码, 2: 企业中的二维码
```

### 命令

#### 同步联系方式

同步所有企业微信联系方式：

```bash
php bin/console wechat-work:sync-contact-ways
```

该命令会：
- 从企业微信 API 获取所有联系方式
- 更新现有联系方式或创建新的联系方式
- 通过定时任务每日 6:01 AM 自动运行

### 请求类

Bundle 包含了多个企业微信 API 请求类：

- `AddContactWayRequest` - 添加新的联系方式
- `UpdateContactWayRequest` - 更新现有联系方式
- `DeleteContactWayRequest` - 删除联系方式
- `GetContactWayRequest` - 获取联系方式详情
- `ListContactWayRequest` - 列出所有联系方式
- `CloseTempChatRequest` - 关闭临时会话

### 仓储

使用 `ContactWayRepository` 查询联系方式：

```php
use WechatWorkContactWayBundle\Repository\ContactWayRepository;

// 在控制器或服务中
public function __construct(private ContactWayRepository $contactWayRepository)
{
}

// 通过配置 ID 查找
$contactWay = $this->contactWayRepository->findOneBy(['configId' => 'config_id']);

// 查找所有活跃的联系方式
$contactWays = $this->contactWayRepository->findAll();
```

### 事件监听器

Bundle 包含自动管理联系方式数据的事件监听器：

- `ContactWayListener` - 处理联系方式实体生命周期事件
- 当实体持久化时自动更新联系方式信息

## 高级用法

### 自定义联系方式处理

对于高级场景，您可以使用自定义处理逻辑扩展同步过程：

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
        
        // 自定义业务逻辑
        $this->applyCustomRules($contactWay, $data);
        
        return $contactWay;
    }
    
    private function applyCustomRules(ContactWay $contactWay, array $data): void
    {
        // 实现您的自定义业务规则
        if ($data['scene'] === 1) {
            $contactWay->setRemark('自动生成的二维码联系方式');
        }
    }
}
```

### 批量操作

高效处理多个联系方式：

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

### 事件驱动架构

监听联系方式事件以实现自定义集成：

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
            // 处理新联系方式创建
            $this->handleNewContactWay($entity);
        }
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof ContactWay) {
            // 处理联系方式更新
            $this->handleContactWayUpdate($entity);
        }
    }
    
    private function handleNewContactWay(ContactWay $contactWay): void
    {
        // 新联系方式的自定义逻辑
    }
    
    private function handleContactWayUpdate(ContactWay $contactWay): void
    {
        // 联系方式更新的自定义逻辑
    }
}
```

## 最佳实践

### 性能优化

- 对于复杂查询使用仓储查询而不是实体管理器
- 利用内置的同步命令进行定期更新
- 频繁请求时监控 API 频率限制

### 安全

- 在处理前验证所有输入数据
- 对敏感配置使用环境变量
- 为 API 失败实施适当的错误处理

### 错误处理

```php
try {
    $contactWay = $this->contactWayRepository->findOneBy(['configId' => $configId]);
    if (!$contactWay) {
        throw new \RuntimeException('Contact way not found');
    }
} catch (\Exception $e) {
    // 适当处理错误
    $this->logger->error('Contact way operation failed', ['error' => $e->getMessage()]);
}
```

## 测试

运行测试套件：

```bash
# 运行所有测试
vendor/bin/phpunit packages/wechat-work-contact-way-bundle/tests

# 运行代码覆盖率测试
vendor/bin/phpunit --coverage-html coverage packages/wechat-work-contact-way-bundle/tests

# 运行 PHPStan 分析
vendor/bin/phpstan analyse packages/wechat-work-contact-way-bundle
```

Bundle 包含全面的测试覆盖：
- 所有请求类的单元测试
- 仓储操作的集成测试
- 各种场景的命令测试
- 实体验证和生命周期事件测试

## API 参考

### ContactWay 实体属性

- `configId` - 企业微信的唯一配置 ID
- `type` - 联系方式类型（1: 单人, 2: 多人）
- `scene` - 使用场景（1: 群聊中的二维码, 2: 企业中的二维码）
- `style` - 小程序控件样式
- `remark` - 备注信息
- `skipVerify` - 添加时是否跳过验证
- `state` - 用于跟踪的渠道参数
- `qrCode` - 二维码 URL
- `user` - 用户 ID 数组
- `party` - 部门 ID 数组
- `temp` - 是否为临时会话
- `expiresIn` - 临时会话二维码过期时间
- `chatExpiresIn` - 临时会话过期时间
- `unionId` - 临时会话联合 ID
- `conclusions` - 结束语消息

## 系统要求

- PHP 8.1 或更高版本
- Symfony 6.4 或更高版本
- Doctrine ORM 3.0 或更高版本

## 贡献

请查看 [CONTRIBUTING](CONTRIBUTING.md) 了解详情。

## 安全

如果您发现任何安全相关问题，请发送邮件至 security@tourze.com 而不是使用问题跟踪器。

## 许可证

MIT 许可证。请查看 [License File](LICENSE) 获取更多信息。
