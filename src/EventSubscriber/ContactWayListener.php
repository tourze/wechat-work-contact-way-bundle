<?php

declare(strict_types=1);

namespace WechatWorkContactWayBundle\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use WechatWorkBundle\Service\WorkService;
use WechatWorkContactWayBundle\Entity\ContactWay;
use WechatWorkContactWayBundle\Request\AddContactWayRequest;
use WechatWorkContactWayBundle\Request\DeleteContactWayRequest;
use WechatWorkContactWayBundle\Request\UpdateContactWayRequest;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: ContactWay::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: ContactWay::class)]
#[AsEntityListener(event: Events::preRemove, method: 'preRemove', entity: ContactWay::class)]
class ContactWayListener
{
    public function __construct(
        private readonly WorkService $workService,
    ) {
    }

    /**
     * 保存数据前，先从远程拿到 configID
     */
    public function prePersist(ContactWay $object): void
    {
        // 如果已经有 configId，说明是测试环境或已经设置，跳过 API 调用
        if (null !== $object->getConfigId()) {
            return;
        }

        $request = AddContactWayRequest::createFromObject($object);
        $response = $this->workService->request($request);

        if (!is_array($response)) {
            return;
        }

        if (isset($response['config_id']) && is_string($response['config_id'])) {
            $object->setConfigId($response['config_id']);
        }
        if (isset($response['qr_code']) && is_string($response['qr_code'])) {
            $object->setQrCode($response['qr_code']);
        }
    }

    /**
     * 保存数据前，先更新远程
     */
    public function preUpdate(ContactWay $object): void
    {
        $request = UpdateContactWayRequest::createFromObject($object);
        $configId = $object->getConfigId();
        if (null !== $configId) {
            $request->setConfigId($configId);
        }
        /** @phpstan-ignore method.notFound */
        $this->workService->asyncRequest($request);
    }

    /**
     * 删除本地记录前，先删远程的记录
     */
    public function preRemove(ContactWay $object): void
    {
        // 简化处理：临时对话的结束逻辑需要外部用户信息，
        // 目前暂时跳过这部分功能，仅删除远程配置

        $request = new DeleteContactWayRequest();
        $configId = $object->getConfigId();
        if (null !== $configId) {
            $request->setConfigId($configId);
        }
        $request->setAgent($object->getAgent());
        /** @phpstan-ignore method.notFound */
        $this->workService->asyncRequest($request);
    }
}
