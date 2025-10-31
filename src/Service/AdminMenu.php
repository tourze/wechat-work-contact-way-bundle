<?php

declare(strict_types=1);

namespace WechatWorkContactWayBundle\Service;

use Knp\Menu\ItemInterface;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use WechatWorkContactWayBundle\Entity\ContactWay;

readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(private LinkGeneratorInterface $linkGenerator)
    {
    }

    public function __invoke(ItemInterface $item): void
    {
        if (null === $item->getChild('企业微信')) {
            $item->addChild('企业微信', [
                'attributes' => [
                    'icon' => 'icon icon-wechat',
                ],
            ]);
        }

        $wechatWorkMenu = $item->getChild('企业微信');
        if (null !== $wechatWorkMenu) {
            $wechatWorkMenu->addChild('联系我方式')->setUri($this->linkGenerator->getCurdListPage(ContactWay::class));
        }
    }
}
