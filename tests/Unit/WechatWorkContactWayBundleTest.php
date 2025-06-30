<?php

namespace WechatWorkContactWayBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use WechatWorkContactWayBundle\WechatWorkContactWayBundle;

class WechatWorkContactWayBundleTest extends TestCase
{
    public function testBundleInstance(): void
    {
        $bundle = new WechatWorkContactWayBundle();
        
        $this->assertInstanceOf(Bundle::class, $bundle);
    }
}