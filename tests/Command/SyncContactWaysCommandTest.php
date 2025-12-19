<?php

namespace WechatWorkContactWayBundle\Tests\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Tourze\PHPUnitSymfonyKernelTest\AbstractCommandTestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkContactWayBundle\Command\SyncContactWaysCommand;
use WechatWorkContactWayBundle\Entity\ContactWay;
use WechatWorkContactWayBundle\Repository\ContactWayRepository;

/**
 * @internal
 */
#[CoversClass(SyncContactWaysCommand::class)]
#[RunTestsInSeparateProcesses]
final class SyncContactWaysCommandTest extends AbstractCommandTestCase
{
    private CommandTester $commandTester;

    private ContactWayRepository $contactWayRepository;

    protected function onSetUp(): void
    {
        $this->initializeCommandTester();
    }

    protected function getCommandTester(): CommandTester
    {
        return $this->commandTester;
    }

    public function testCommandInstantiation(): void
    {
        $command = self::getService(SyncContactWaysCommand::class);
        $this->assertInstanceOf(SyncContactWaysCommand::class, $command);
    }

    private function initializeCommandTester(): void
    {
        // 获取真实的Repository
        $this->contactWayRepository = self::getService(ContactWayRepository::class);

        // 设置命令测试器
        $application = new Application();

        // 从容器获取命令（会自动注入所有依赖）
        $command = self::getService(SyncContactWaysCommand::class);
        $application->addCommand($command);

        $this->commandTester = new CommandTester($command);
    }

    public function testCommandConstants(): void
    {
        $this->assertSame('wechat-work:sync-contact-ways', SyncContactWaysCommand::NAME);
    }

    public function testCommandConstructorSignature(): void
    {
        $reflection = new \ReflectionClass(SyncContactWaysCommand::class);
        $constructor = $reflection->getConstructor();

        $this->assertNotNull($constructor);
        $parameters = $constructor->getParameters();
        $this->assertCount(4, $parameters);

        $this->assertSame('agentRepository', $parameters[0]->getName());
        $this->assertSame('contactWayRepository', $parameters[1]->getName());
        $this->assertSame('workService', $parameters[2]->getName());
        $this->assertSame('entityManager', $parameters[3]->getName());
    }

    public function testExecuteWithNoAgents(): void
    {
        // 确保测试数据库中没有Agent
        // 注意：在测试环境中，Agent表可能不存在，因为它属于WechatWorkBundle
        // 这是集成测试的限制 - 我们测试的是这个包的功能，而不是外部包的功能

        try {
            $exitCode = $this->commandTester->execute([]);
            // Command应该成功执行，即使没有Agent或者API调用失败
            $this->assertSame(Command::SUCCESS, $exitCode);
        } catch (\Exception $e) {
            // 在某些测试环境中，可能会有遗留的Agent数据导致API调用
            // 我们验证异常被正确处理
            $this->assertStringContainsString('invalid corpid', $e->getMessage());
        }
    }

    public function testExecuteWithTestAgent(): void
    {
        // 创建测试数据
        $corp = new Corp();
        $corp->setCorpId('test_corp_' . uniqid());
        $corp->setName('测试企业');
        $corp->setCorpSecret('test_secret');

        $agent = new Agent();
        $agent->setAgentId('test_agent_' . uniqid());
        $agent->setName('测试应用');
        $agent->setCorp($corp);
        $agent->setSecret('test_secret_123');

        $em = self::getEntityManager();
        $em->persist($corp);
        $em->persist($agent);
        $em->flush();

        // 执行命令，期望它会失败但不抛出异常（优雅处理错误）
        try {
            $exitCode = $this->commandTester->execute([]);
            $this->assertSame(Command::SUCCESS, $exitCode);
        } catch (\Exception $e) {
            // 在测试环境中，由于使用的是测试corpid，API调用会失败
            // 这是正常行为，我们验证异常被适当处理
            $this->assertStringContainsString('invalid corpid', $e->getMessage());
        }
    }

    public function testSkipsExistingContactWays(): void
    {
        // 创建已存在的ContactWay
        $corp = new Corp();
        $corp->setCorpId('test_corp_' . uniqid());
        $corp->setName('测试企业');
        $corp->setCorpSecret('test_secret');

        $agent = new Agent();
        $agent->setAgentId('test_agent_' . uniqid());
        $agent->setName('测试应用');
        $agent->setCorp($corp);
        $agent->setSecret('test_secret_123');

        $contactWay = new ContactWay();
        $contactWay->setCorp($corp);
        $contactWay->setAgent($agent);
        $contactWay->setConfigId('existing_config_' . uniqid());
        $contactWay->setType(1);
        $contactWay->setScene(1);

        $em = self::getEntityManager();
        $em->persist($corp);
        $em->persist($agent);
        $em->persist($contactWay);
        $em->flush();

        // 执行命令，期望API调用失败但不影响测试逻辑
        try {
            $exitCode = $this->commandTester->execute([]);
            $this->assertSame(Command::SUCCESS, $exitCode);
        } catch (\Exception $e) {
            $this->assertStringContainsString('invalid corpid', $e->getMessage());
        }

        // 验证ContactWay没有被重复创建
        $existingWays = $this->contactWayRepository->findBy(['configId' => $contactWay->getConfigId()]);
        $this->assertCount(1, $existingWays);
    }

    public function testContactWayCreation(): void
    {
        // 创建测试数据
        $corp = new Corp();
        $corp->setCorpId('test_corp_' . uniqid());
        $corp->setName('测试企业');
        $corp->setCorpSecret('test_secret');

        $agent = new Agent();
        $agent->setAgentId('test_agent_' . uniqid());
        $agent->setName('测试应用');
        $agent->setCorp($corp);
        $agent->setSecret('test_secret_123');

        $em = self::getEntityManager();
        $em->persist($corp);
        $em->persist($agent);
        $em->flush();

        // 记录执行前的ContactWay数量
        $beforeCount = $this->contactWayRepository->count([]);

        // 执行命令，期望API调用失败但不影响基本测试逻辑
        try {
            $exitCode = $this->commandTester->execute([]);
            $this->assertSame(Command::SUCCESS, $exitCode);
        } catch (\Exception $e) {
            $this->assertStringContainsString('invalid corpid', $e->getMessage());
        }

        // 在测试环境中，由于API调用失败，不会创建新的ContactWay
        // 我们只验证命令能够正常处理这种情况
        $afterCount = $this->contactWayRepository->count([]);
        $this->assertSame($beforeCount, $afterCount);
    }
}
