<?php

namespace WechatWorkContactWayBundle;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;
use WechatWorkBundle\WechatWorkBundle;

class WechatWorkContactWayBundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            DoctrineBundle::class => ['all' => true],
            WechatWorkBundle::class => ['all' => true],
        ];
    }
}
