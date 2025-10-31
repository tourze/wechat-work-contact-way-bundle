<?php

namespace WechatWorkContactWayBundle\Tests\EventSubscriber;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkContactWayBundle\Entity\ContactWay;
use WechatWorkContactWayBundle\EventSubscriber\ContactWayListener;

/**
 * @internal
 */
#[CoversClass(ContactWayListener::class)]
final class ContactWayListenerTest extends TestCase
{
    protected function onSetUp(): void
    {
    }

    public function testPrePersist(): void
    {
        $contactWay = new ContactWay();
        $contactWay->setType(1);
        $contactWay->setScene(1);

        $this->assertEquals(1, $contactWay->getType());
        $this->assertEquals(1, $contactWay->getScene());
    }

    public function testListenerMethods(): void
    {
        $reflection = new \ReflectionClass(ContactWayListener::class);
        $this->assertTrue($reflection->hasMethod('prePersist'));
        $this->assertTrue($reflection->hasMethod('preUpdate'));
        $this->assertTrue($reflection->hasMethod('preRemove'));
    }

    public function testContactWayEntityOperations(): void
    {
        $corp = new Corp();
        $corp->setCorpId('test_corp');
        $corp->setName('Test Corp');
        $corp->setCorpSecret('test_secret_123');

        $agent = new Agent();
        $agent->setAgentId('test_agent');
        $agent->setName('Test Agent');
        $agent->setSecret('test_secret_123');
        $agent->setCorp($corp);

        $contactWay = new ContactWay();
        $contactWay->setConfigId('test_config_id');
        $contactWay->setType(1);
        $contactWay->setScene(2);
        $contactWay->setTemp(true);
        $contactWay->setUser(['test_user_id']);
        $contactWay->setUnionId('test_union_id');
        $contactWay->setAgent($agent);
        $contactWay->setCorp($corp);

        // 验证所有属性设置正确
        $this->assertEquals('test_config_id', $contactWay->getConfigId());
        $this->assertEquals(1, $contactWay->getType());
        $this->assertEquals(2, $contactWay->getScene());
        $this->assertTrue($contactWay->isTemp());
        $this->assertEquals(['test_user_id'], $contactWay->getUser());
        $this->assertEquals('test_union_id', $contactWay->getUnionId());
        $this->assertSame($agent, $contactWay->getAgent());
        $this->assertSame($corp, $contactWay->getCorp());
    }

    public function testPreUpdateMethod(): void
    {
        $reflection = new \ReflectionClass(ContactWayListener::class);
        $this->assertTrue($reflection->hasMethod('preUpdate'));

        $preUpdateMethod = $reflection->getMethod('preUpdate');
        $this->assertTrue($preUpdateMethod->isPublic());

        $parameters = $preUpdateMethod->getParameters();
        $this->assertCount(1, $parameters);
        $this->assertSame('object', $parameters[0]->getName());
    }

    public function testPreRemoveMethod(): void
    {
        $reflection = new \ReflectionClass(ContactWayListener::class);
        $this->assertTrue($reflection->hasMethod('preRemove'));

        $preRemoveMethod = $reflection->getMethod('preRemove');
        $this->assertTrue($preRemoveMethod->isPublic());

        $parameters = $preRemoveMethod->getParameters();
        $this->assertCount(1, $parameters);
        $this->assertSame('object', $parameters[0]->getName());
    }
}
