<?php

declare(strict_types=1);

namespace WechatWorkContactWayBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\Symfony\CronJob\Attribute\AsCronTask;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Repository\AgentRepository;
use WechatWorkBundle\Service\WorkService;
use WechatWorkContactWayBundle\Entity\ContactWay;
use WechatWorkContactWayBundle\Repository\ContactWayRepository;
use WechatWorkContactWayBundle\Request\GetContactWayRequest;
use WechatWorkContactWayBundle\Request\ListContactWayRequest;

#[AsCronTask(expression: '1 6 * * *')]
#[AsCommand(name: self::NAME, description: '同步获取联系我的方式')]
#[Autoconfigure(public: true)]
class SyncContactWaysCommand extends Command
{
    public const NAME = 'wechat-work:sync-contact-ways';

    public function __construct(
        private readonly AgentRepository $agentRepository,
        private readonly ContactWayRepository $contactWayRepository,
        private readonly WorkService $workService,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->agentRepository->findAll() as $agent) {
            $this->syncContactWaysForAgent($agent);
        }

        return Command::SUCCESS;
    }

    private function syncContactWaysForAgent(Agent $agent): void
    {
        $cursor = null;

        do {
            $listRequest = new ListContactWayRequest();
            $listRequest->setAgent($agent);
            $listRequest->setCursor($cursor);

            $response = $this->workService->request($listRequest);
            if (!is_array($response)) {
                break;
            }

            $cursor = is_string($response['next_cursor'] ?? null) ? $response['next_cursor'] : null;

            $contactWays = $response['contact_way'] ?? [];
            if (!is_array($contactWays)) {
                continue;
            }

            foreach ($contactWays as $item) {
                if (is_array($item) && isset($item['config_id'])) {
                    $this->processContactWayItem($agent, $item);
                }
            }
        } while (null !== $cursor);
    }

    /**
     * @param array<string, mixed> $item
     */
    private function processContactWayItem(Agent $agent, array $item): void
    {
        $configId = $item['config_id'] ?? null;
        if (!is_string($configId)) {
            return;
        }

        $way = $this->contactWayRepository->findOneBy(['configId' => $configId]);
        if (null !== $way) {
            return;
        }

        $way = $this->createContactWay($agent, $configId);
        $this->populateContactWayDetails($way);

        $this->entityManager->persist($way);
        $this->entityManager->flush();
    }

    private function createContactWay(Agent $agent, string $configId): ContactWay
    {
        $way = new ContactWay();
        $way->setAgent($agent);
        $way->setCorp($agent->getCorp());
        $way->setConfigId($configId);

        return $way;
    }

    private function populateContactWayDetails(ContactWay $way): void
    {
        $contactWayData = $this->fetchContactWayData($way);
        if (null === $contactWayData) {
            return;
        }

        $this->setBasicContactWayFields($way, $contactWayData);
        $this->setOptionalContactWayFields($way, $contactWayData);
        $this->setUserAndPartyFields($way, $contactWayData);
        $this->setTempSessionFields($way, $contactWayData);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function fetchContactWayData(ContactWay $way): ?array
    {
        $detailRequest = new GetContactWayRequest();
        $detailRequest->setAgent($way->getAgent());
        $configId = $way->getConfigId();
        if (null !== $configId) {
            $detailRequest->setConfigId($configId);
        }
        $detailResponse = $this->workService->request($detailRequest);

        if (!is_array($detailResponse)) {
            return null;
        }

        $contactWayData = $detailResponse['contact_way'] ?? null;

        if (!is_array($contactWayData)) {
            return null;
        }

        /** @var array<string, mixed> $contactWayData */
        return $contactWayData;
    }

    /**
     * @param array<string, mixed> $contactWayData
     */
    private function setBasicContactWayFields(ContactWay $way, array $contactWayData): void
    {
        if (isset($contactWayData['type']) && is_int($contactWayData['type'])) {
            $way->setType($contactWayData['type']);
        }
        if (isset($contactWayData['scene']) && is_int($contactWayData['scene'])) {
            $way->setScene($contactWayData['scene']);
        }
    }

    /**
     * @param array<string, mixed> $contactWayData
     */
    private function setOptionalContactWayFields(ContactWay $way, array $contactWayData): void
    {
        $this->setStyleField($way, $contactWayData);
        $this->setRemarkField($way, $contactWayData);
        $this->setSkipVerifyField($way, $contactWayData);
        $this->setStateField($way, $contactWayData);
        $this->setQrCodeField($way, $contactWayData);
    }

    /**
     * @param array<string, mixed> $contactWayData
     */
    private function setStyleField(ContactWay $way, array $contactWayData): void
    {
        if (isset($contactWayData['style']) && is_int($contactWayData['style'])) {
            $way->setStyle($contactWayData['style']);
        }
    }

    /**
     * @param array<string, mixed> $contactWayData
     */
    private function setRemarkField(ContactWay $way, array $contactWayData): void
    {
        if (isset($contactWayData['remark']) && is_string($contactWayData['remark'])) {
            $way->setRemark($contactWayData['remark']);
        }
    }

    /**
     * @param array<string, mixed> $contactWayData
     */
    private function setSkipVerifyField(ContactWay $way, array $contactWayData): void
    {
        if (isset($contactWayData['skip_verify']) && is_bool($contactWayData['skip_verify'])) {
            $way->setSkipVerify($contactWayData['skip_verify']);
        }
    }

    /**
     * @param array<string, mixed> $contactWayData
     */
    private function setStateField(ContactWay $way, array $contactWayData): void
    {
        if (isset($contactWayData['state']) && is_string($contactWayData['state'])) {
            $way->setState($contactWayData['state']);
        }
    }

    /**
     * @param array<string, mixed> $contactWayData
     */
    private function setQrCodeField(ContactWay $way, array $contactWayData): void
    {
        if (isset($contactWayData['qr_code']) && is_string($contactWayData['qr_code'])) {
            $way->setQrCode($contactWayData['qr_code']);
        }
    }

    /**
     * @param array<string, mixed> $contactWayData
     */
    private function setUserAndPartyFields(ContactWay $way, array $contactWayData): void
    {
        if (isset($contactWayData['user']) && is_array($contactWayData['user'])) {
            $userArray = array_filter($contactWayData['user'], 'is_string');
            $way->setUser($userArray);
        }
        if (isset($contactWayData['party']) && is_array($contactWayData['party'])) {
            $partyArray = array_filter($contactWayData['party'], 'is_string');
            $way->setParty($partyArray);
        }
    }

    /**
     * @param array<string, mixed> $contactWayData
     */
    private function setTempSessionFields(ContactWay $way, array $contactWayData): void
    {
        $this->setTempFlag($way, $contactWayData);
        $this->setTempExpires($way, $contactWayData);
        $this->setChatExpires($way, $contactWayData);
        $this->setUnionIdField($way, $contactWayData);
        $this->setConclusionsField($way, $contactWayData);
    }

    /**
     * @param array<string, mixed> $contactWayData
     */
    private function setTempFlag(ContactWay $way, array $contactWayData): void
    {
        if (isset($contactWayData['is_temp']) && is_bool($contactWayData['is_temp'])) {
            $way->setTemp($contactWayData['is_temp']);
        }
    }

    /**
     * @param array<string, mixed> $contactWayData
     */
    private function setTempExpires(ContactWay $way, array $contactWayData): void
    {
        if (isset($contactWayData['expires_in']) && is_int($contactWayData['expires_in'])) {
            $way->setExpiresIn($contactWayData['expires_in']);
        }
    }

    /**
     * @param array<string, mixed> $contactWayData
     */
    private function setChatExpires(ContactWay $way, array $contactWayData): void
    {
        if (isset($contactWayData['chat_expires_in']) && is_int($contactWayData['chat_expires_in'])) {
            $way->setChatExpiresIn($contactWayData['chat_expires_in']);
        }
    }

    /**
     * @param array<string, mixed> $contactWayData
     */
    private function setUnionIdField(ContactWay $way, array $contactWayData): void
    {
        if (isset($contactWayData['unionid']) && is_string($contactWayData['unionid'])) {
            $way->setUnionId($contactWayData['unionid']);
        }
    }

    /**
     * @param array<string, mixed> $contactWayData
     */
    private function setConclusionsField(ContactWay $way, array $contactWayData): void
    {
        if (isset($contactWayData['conclusions']) && is_array($contactWayData['conclusions'])) {
            $way->setConclusions($contactWayData['conclusions']);
        }
    }
}
