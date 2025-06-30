<?php

namespace WechatWorkContactWayBundle\Tests\Integration\EventSubscriber;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Tourze\WechatWorkContracts\AgentInterface;
use Tourze\WechatWorkContracts\CorpInterface;
use Tourze\WechatWorkExternalContactModel\ExternalContactInterface;
use Tourze\WechatWorkExternalContactModel\ExternalUserLoaderInterface;
use WechatWorkBundle\Service\WorkService;
use WechatWorkContactWayBundle\Entity\ContactWay;
use WechatWorkContactWayBundle\EventSubscriber\ContactWayListener;

class ContactWayListenerTest extends TestCase
{
    private MockObject $workService;
    private MockObject $userLoader;
    private MockObject $logger;
    private ContactWayListener $listener;

    protected function setUp(): void
    {
        $this->workService = $this->createMock(WorkService::class);
        $this->userLoader = $this->createMock(ExternalUserLoaderInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        
        $this->listener = new ContactWayListener(
            $this->workService,
            $this->userLoader,
            $this->logger
        );
    }

    public function testPrePersist(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setType(1); // 设置必需的类型
        $contactWay->setScene(1); // 设置必需的场景
        
        $this->workService
            ->expects($this->once())
            ->method('request')
            ->willReturn([
                'config_id' => 'test_config_id',
                'qr_code' => 'test_qr_code'
            ]);
        
        $this->listener->prePersist($contactWay);
        
        $this->assertEquals('test_config_id', $contactWay->getConfigId());
        $this->assertEquals('test_qr_code', $contactWay->getQrCode());
    }

    public function testPreUpdate(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setConfigId('existing_config_id');
        $contactWay->setType(1); // 设置必需的类型
        $contactWay->setScene(1); // 设置必需的场景
        
        $this->workService
            ->expects($this->once())
            ->method('asyncRequest');
        
        $this->listener->preUpdate($contactWay);
    }

    public function testPreRemoveWithoutTempChat(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setConfigId('test_config_id');
        
        $agent = $this->createMock(AgentInterface::class);
        $contactWay->setAgent($agent);
        
        $this->workService
            ->expects($this->once())
            ->method('asyncRequest');
        
        $this->listener->preRemove($contactWay);
    }

    public function testPreRemoveWithTempChat(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setConfigId('test_config_id');
        
        $agent = $this->createMock(AgentInterface::class);
        $contactWay->setAgent($agent);
        $contactWay->setTemp(true);
        $contactWay->setUser(['user1']);
        $contactWay->setUnionId('union_id');
        
        $corp = $this->createMock(CorpInterface::class);
        $contactWay->setCorp($corp);
        
        $externalUser = $this->createMock(ExternalContactInterface::class);
        $externalUser->method('getExternalUserId')->willReturn('external_user_id');
        
        $this->userLoader
            ->expects($this->once())
            ->method('loadByUnionIdAndCorp')
            ->with('union_id', $corp)
            ->willReturn($externalUser);
        
        $this->workService
            ->expects($this->once())
            ->method('request')
            ->willReturn([]);
            
        $this->workService
            ->expects($this->once())
            ->method('asyncRequest');
        
        $this->listener->preRemove($contactWay);
    }
}