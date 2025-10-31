<?php

declare(strict_types=1);

namespace WechatWorkContactWayBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use WechatWorkBundle\DataFixtures\AgentFixtures;
use WechatWorkBundle\DataFixtures\CorpFixtures;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkContactWayBundle\Entity\ContactWay;

class ContactWayFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // 获取已存在的企业和代理
        $corp = $this->getReference(CorpFixtures::CORP_1_REFERENCE, Corp::class);
        $agent = $this->getReference(AgentFixtures::AGENT_1_REFERENCE, Agent::class);

        $contactWay = new ContactWay();
        $contactWay->setCorp($corp);
        $contactWay->setAgent($agent);
        $contactWay->setConfigId('test_config_001');
        $contactWay->setType(1);
        $contactWay->setScene(1);
        $contactWay->setStyle(1);
        $contactWay->setRemark('测试联系人方式');
        $contactWay->setSkipVerify(false);
        $contactWay->setState('test_state');
        $contactWay->setUser(['test_user_001']);
        $contactWay->setParty(['1', '2']);
        $contactWay->setTemp(false);
        $contactWay->setExpiresIn(86400);
        $contactWay->setChatExpiresIn(3600);
        $contactWay->setUnionId('test_union_001');
        $contactWay->setExclusive(false);
        $contactWay->setConclusions(['test_conclusion']);
        $contactWay->setQrCode('https://cdn.jsdelivr.net/gh/user/repo@main/qrcode.png');

        $manager->persist($contactWay);

        $tempContactWay = new ContactWay();
        $tempContactWay->setCorp($corp);
        $tempContactWay->setAgent($agent);
        $tempContactWay->setConfigId('temp_config_001');
        $tempContactWay->setType(2);
        $tempContactWay->setScene(2);
        $tempContactWay->setStyle(2);
        $tempContactWay->setRemark('临时联系人方式');
        $tempContactWay->setSkipVerify(true);
        $tempContactWay->setState('temp_state');
        $tempContactWay->setUser(['temp_user_001', 'temp_user_002']);
        $tempContactWay->setTemp(true);
        $tempContactWay->setExpiresIn(7200);
        $tempContactWay->setChatExpiresIn(1800);
        $tempContactWay->setExclusive(true);
        $tempContactWay->setConclusions(['temp_conclusion_1', 'temp_conclusion_2']);

        $manager->persist($tempContactWay);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CorpFixtures::class,
            AgentFixtures::class,
        ];
    }
}
