<?php

namespace WechatWorkContactWayBundle\Tests\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use WechatWorkContactWayBundle\DependencyInjection\WechatWorkContactWayExtension;

class WechatWorkContactWayExtensionTest extends TestCase
{
    public function testLoad(): void
    {
        $extension = new WechatWorkContactWayExtension();
        $container = new ContainerBuilder();
        
        // 测试扩展能正常加载配置而不抛出异常
        $extension->load([], $container);
        
        // 检查已加载的配置数量，确保服务配置文件被处理
        $this->assertGreaterThan(0, count($container->getDefinitions()));
    }
}