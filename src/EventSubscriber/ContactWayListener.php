<?php

namespace WechatWorkContactWayBundle\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;
use WechatWorkBundle\Service\WorkService;
use WechatWorkContactWayBundle\Entity\ContactWay;
use WechatWorkContactWayBundle\Request\AddContactWayRequest;
use WechatWorkContactWayBundle\Request\CloseTempChatRequest;
use WechatWorkContactWayBundle\Request\DeleteContactWayRequest;
use WechatWorkContactWayBundle\Request\UpdateContactWayRequest;
use WechatWorkExternalContactBundle\Repository\ExternalUserRepository;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: ContactWay::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: ContactWay::class)]
#[AsEntityListener(event: Events::preRemove, method: 'preRemove', entity: ContactWay::class)]
class ContactWayListener
{
    public function __construct(
        private readonly WorkService $workService,
        private readonly ExternalUserRepository $externalUserRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * 保存数据前，先从远程拿到 configID
     */
    public function prePersist(ContactWay $object): void
    {
        $request = AddContactWayRequest::createFromObject($object);
        $response = $this->workService->request($request);

        $object->setConfigId($response['config_id']);
        $object->setQrCode($response['qr_code']);
    }

    /**
     * 保存数据前，先更新远程
     */
    public function preUpdate(ContactWay $object): void
    {
        $request = UpdateContactWayRequest::createFromObject($object);
        $request->setConfigId($object->getConfigId());
        $this->workService->asyncRequest($request);
    }

    /**
     * 删除本地记录前，先删远程的记录
     */
    public function preRemove(ContactWay $object): void
    {
        // 先结束临时对话
        if ($object->isTemp() && !empty($object->getUser()) && !empty($object->getUnionId())) {
            $user = $this->externalUserRepository->findOneBy([
                'unionId' => $object->getUnionId(),
                'corp' => $object->getCorp(),
            ]);
            if ($user) {
                $request = new CloseTempChatRequest();
                $request->setUserId($object->getUser()[0]);
                $request->setExternalUserId($user->getExternalUserId());
                $request->setAgent($object->getAgent());
                try {
                    $this->workService->request($request);
                } catch (\Throwable $exception) {
                    $this->logger->error('结束临时对话失败', [
                        'contactWay' => $object,
                        'user' => $user,
                        'exception' => $exception,
                    ]);
                }
            }
        }

        $request = new DeleteContactWayRequest();
        $request->setConfigId($object->getConfigId());
        $request->setAgent($object->getAgent());
        $this->workService->asyncRequest($request);
    }

    // TODO 定时同步是否需要实现？
}
