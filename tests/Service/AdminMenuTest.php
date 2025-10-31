<?php

declare(strict_types=1);

namespace WechatWorkContactWayBundle\Tests\Service;

use Knp\Menu\ItemInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;
use WechatWorkContactWayBundle\Service\AdminMenu;

/**
 * @internal
 */
#[CoversClass(AdminMenu::class)]
#[RunTestsInSeparateProcesses]
final class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    private AdminMenu $adminMenu;

    protected function onSetUp(): void
    {
        $this->adminMenu = self::getService(AdminMenu::class);
    }

    public function testInvokeAddsMenuItems(): void
    {
        $item = $this->createMock(ItemInterface::class);
        $wechatWorkChild = $this->createMock(ItemInterface::class);

        $item->expects(self::exactly(2))
            ->method('getChild')
            ->willReturnCallback($this->createGetChildCallback($wechatWorkChild))
        ;

        $item->expects(self::once())
            ->method('addChild')
            ->with('企业微信', [
                'attributes' => [
                    'icon' => 'icon icon-wechat',
                ],
            ])
            ->willReturn($wechatWorkChild)
        ;

        $wechatWorkChild->expects(self::once())
            ->method('addChild')
            ->with('联系我方式')
            ->willReturnCallback(function () {
                $child = $this->createMock(ItemInterface::class);
                $child->expects(self::once())->method('setUri');

                return $child;
            })
        ;

        $this->adminMenu->__invoke($item);
        $this->assertInstanceOf(AdminMenu::class, $this->adminMenu);
    }

    public function testInvokeWithExistingWechatWorkMenu(): void
    {
        $item = $this->createMock(ItemInterface::class);
        $wechatWorkChild = $this->createMock(ItemInterface::class);

        // 模拟"企业微信"菜单已存在的情况
        $item->expects(self::exactly(2))
            ->method('getChild')
            ->with('企业微信')
            ->willReturn($wechatWorkChild)
        ;

        $item->expects(self::never())
            ->method('addChild')
        ;

        $wechatWorkChild->expects(self::once())
            ->method('addChild')
            ->with('联系我方式')
            ->willReturnCallback(function () {
                $child = $this->createMock(ItemInterface::class);
                $child->expects(self::once())->method('setUri');

                return $child;
            })
        ;

        $this->adminMenu->__invoke($item);
        $this->assertInstanceOf(AdminMenu::class, $this->adminMenu);
    }

    public function testInvokeAddsContactWayMenuOnly(): void
    {
        $item = $this->createMock(ItemInterface::class);
        $wechatWorkChild = $this->createMock(ItemInterface::class);

        $item->expects(self::exactly(2))
            ->method('getChild')
            ->willReturnCallback($this->createGetChildCallback($wechatWorkChild))
        ;

        $item->expects(self::once())
            ->method('addChild')
            ->willReturn($wechatWorkChild)
        ;

        // 验证只添加了联系我方式这一个菜单项
        $wechatWorkChild->expects(self::once())
            ->method('addChild')
            ->with('联系我方式')
            ->willReturnCallback(function () {
                $child = $this->createMock(ItemInterface::class);
                $child->expects(self::once())
                    ->method('setUri')
                    ->with(self::stringContains('/admin'))
                ;

                return $child;
            })
        ;

        $this->adminMenu->__invoke($item);
    }

    public function testServiceInstantiatesProperly(): void
    {
        $this->assertInstanceOf(AdminMenu::class, $this->adminMenu);
    }

    public function testMenuStructure(): void
    {
        $item = $this->createMock(ItemInterface::class);
        $wechatWorkChild = $this->createMock(ItemInterface::class);

        $item->expects(self::exactly(2))
            ->method('getChild')
            ->with('企业微信')
            ->willReturnOnConsecutiveCalls(null, $wechatWorkChild)
        ;

        $item->expects(self::once())
            ->method('addChild')
            ->with('企业微信', [
                'attributes' => [
                    'icon' => 'icon icon-wechat',
                ],
            ])
            ->willReturn($wechatWorkChild)
        ;

        $wechatWorkChild->expects(self::once())
            ->method('addChild')
            ->with('联系我方式')
            ->willReturnCallback(function () {
                $child = $this->createMock(ItemInterface::class);
                $child->expects(self::once())->method('setUri');

                return $child;
            })
        ;

        $this->adminMenu->__invoke($item);
    }

    private function createGetChildCallback(ItemInterface $wechatWorkChild): \Closure
    {
        return function ($name) use ($wechatWorkChild) {
            if ('企业微信' === $name) {
                /** @var int $callCount */
                static $callCount = 0;
                $result = 0 === $callCount ? null : $wechatWorkChild;
                ++$callCount;

                return $result;
            }

            return null;
        };
    }
}
