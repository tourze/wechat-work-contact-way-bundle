<?php

namespace WechatWorkContactWayBundle\Tests\Integration\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\TestCase;
use Doctrine\Persistence\ManagerRegistry;
use WechatWorkContactWayBundle\Entity\ContactWay;
use WechatWorkContactWayBundle\Repository\ContactWayRepository;

class ContactWayRepositoryTest extends TestCase
{
    public function testRepositoryInstance(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $repository = new ContactWayRepository($managerRegistry);
        
        $this->assertInstanceOf(ServiceEntityRepository::class, $repository);
    }

    public function testRepositoryEntityClass(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $repository = new ContactWayRepository($managerRegistry);
        
        // 测试 Repository 的构造函数能正常工作而不抛出异常
        $this->assertInstanceOf(ContactWayRepository::class, $repository);
    }
}