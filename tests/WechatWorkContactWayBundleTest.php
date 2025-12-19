<?php

declare(strict_types=1);

namespace WechatWorkContactWayBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use WechatWorkContactWayBundle\WechatWorkContactWayBundle;


#[CoversClass(WechatWorkContactWayBundle::class)]
#[RunTestsInSeparateProcesses]
final class WechatWorkContactWayBundleTest extends AbstractBundleTestCase
{
}
