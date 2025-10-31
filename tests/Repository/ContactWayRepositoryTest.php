<?php

namespace WechatWorkContactWayBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkContactWayBundle\Entity\ContactWay;
use WechatWorkContactWayBundle\Repository\ContactWayRepository;

/**
 * @template-extends AbstractRepositoryTestCase<ContactWay>
 * @internal
 */
#[CoversClass(ContactWayRepository::class)]
#[RunTestsInSeparateProcesses]
final class ContactWayRepositoryTest extends AbstractRepositoryTestCase
{
    private ContactWayRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(ContactWayRepository::class);
    }

    private function createValidContactWay(?string $configId = null): ContactWay
    {
        $corp = new Corp();
        $corpUniqId = uniqid();
        $corp->setCorpId('test_corp_' . $corpUniqId);
        $corp->setName('测试企业_' . $corpUniqId);
        $corp->setCorpSecret('test_secret_123');

        $agent = new Agent();
        $agent->setAgentId('1000');
        $agent->setName('测试应用');
        $agent->setSecret('test_secret_123');
        $agent->setCorp($corp);

        // 持久化关联实体
        $entityManager = self::getService(EntityManagerInterface::class);
        $entityManager->persist($corp);
        $entityManager->persist($agent);
        $entityManager->flush();

        $contactWay = new ContactWay();
        $contactWay->setCorp($corp);
        $contactWay->setAgent($agent);
        $contactWay->setConfigId($configId ?? 'test_config_' . uniqid());
        $contactWay->setType(1);
        $contactWay->setScene(1);

        return $contactWay;
    }

    public function testRepositoryService(): void
    {
        $this->assertInstanceOf(ContactWayRepository::class, $this->repository);
    }

    public function testBasicEntityOperations(): void
    {
        $contactWay = $this->createValidContactWay('test_config_id');

        $this->repository->save($contactWay);

        $found = $this->repository->find($contactWay->getId());
        $this->assertInstanceOf(ContactWay::class, $found);
        $this->assertEquals('test_config_id', $found->getConfigId());
    }

    public function testFindByConfigId(): void
    {
        $contactWay = $this->createValidContactWay('findby_config_id');

        $this->repository->save($contactWay);

        $found = $this->repository->findOneBy(['configId' => 'findby_config_id']);
        $this->assertInstanceOf(ContactWay::class, $found);
        $this->assertEquals('findby_config_id', $found->getConfigId());
    }

    public function testSaveMethod(): void
    {
        $contactWay = $this->createValidContactWay('save_method_test');

        $this->repository->save($contactWay);

        $this->assertNotNull($contactWay->getId());
        $found = $this->repository->find($contactWay->getId());
        $this->assertInstanceOf(ContactWay::class, $found);
    }

    public function testFindByNullStateField(): void
    {
        $contactWay = $this->createValidContactWay('null_state_config');
        $contactWay->setState(null);

        $this->repository->save($contactWay);

        $queryBuilder = $this->repository->createQueryBuilder('c')
            ->where('c.state IS NULL')
        ;
        $found = $queryBuilder->getQuery()->getResult();
        $this->assertIsArray($found);
        $this->assertGreaterThanOrEqual(1, count($found));

        $found = $this->repository->findBy(['state' => null]);
        $this->assertIsArray($found);
        $this->assertGreaterThanOrEqual(1, count($found));
    }

    public function testFindByNullRemarkField(): void
    {
        $contactWay = $this->createValidContactWay('null_remark_config');
        $contactWay->setRemark(null);

        $this->repository->save($contactWay);

        $queryBuilder = $this->repository->createQueryBuilder('c')
            ->where('c.remark IS NULL')
        ;
        $found = $queryBuilder->getQuery()->getResult();
        $this->assertIsArray($found);
        $this->assertGreaterThanOrEqual(1, count($found));

        $found = $this->repository->findBy(['remark' => null]);
        $this->assertIsArray($found);
        $this->assertGreaterThanOrEqual(1, count($found));
    }

    public function testCountByNullStateField(): void
    {
        $contactWay = $this->createValidContactWay('count_null_state_config');
        $contactWay->setState(null);

        $this->repository->save($contactWay);

        $queryBuilder = $this->repository->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.state IS NULL')
        ;
        $count = $queryBuilder->getQuery()->getSingleScalarResult();
        $this->assertGreaterThanOrEqual(1, $count);

        $count = $this->repository->count(['state' => null]);
        $this->assertGreaterThanOrEqual(1, $count);
    }

    public function testCountByNullRemarkField(): void
    {
        $contactWay = $this->createValidContactWay('count_null_remark_config');
        $contactWay->setRemark(null);

        $this->repository->save($contactWay);

        $queryBuilder = $this->repository->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.remark IS NULL')
        ;
        $count = $queryBuilder->getQuery()->getSingleScalarResult();
        $this->assertGreaterThanOrEqual(1, $count);

        $count = $this->repository->count(['remark' => null]);
        $this->assertGreaterThanOrEqual(1, $count);
    }

    public function testRemove(): void
    {
        $contactWay = $this->createValidContactWay('remove_test_config');

        $this->repository->save($contactWay);
        $id = $contactWay->getId();

        $this->repository->remove($contactWay);

        $found = $this->repository->find($id);
        $this->assertNull($found);
    }

    public function testAssociationQueryCapabilities(): void
    {
        $contactWay = $this->createValidContactWay('association_query_config');
        $this->repository->save($contactWay);

        $queryBuilder = $this->repository->createQueryBuilder('c')
            ->where('c.configId = :configId')
            ->setParameter('configId', 'association_query_config')
        ;

        /** @var ContactWay[] $results */
        $results = $queryBuilder->getQuery()->getResult();
        $this->assertCount(1, $results);
        $this->assertIsArray($results);
        $this->assertArrayHasKey(0, $results);
        $this->assertInstanceOf(ContactWay::class, $results[0]);
    }

    public function testCountQueryCapabilities(): void
    {
        $contactWay = $this->createValidContactWay('count_query_config');
        $this->repository->save($contactWay);

        $queryBuilder = $this->repository->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.type = :type')
            ->setParameter('type', 1)
        ;

        $count = $queryBuilder->getQuery()->getSingleScalarResult();
        $this->assertGreaterThanOrEqual(1, $count);
    }

    public function testFindOneByWithOrderBy(): void
    {
        $contactWay1 = $this->createValidContactWay('orderby_test_z');
        $contactWay1->setType(2);
        $this->repository->save($contactWay1);

        $contactWay2 = $this->createValidContactWay('orderby_test_a');
        $contactWay2->setType(2);
        $this->repository->save($contactWay2);

        $result = $this->repository->findOneBy(['type' => 2], ['configId' => 'ASC']);
        $this->assertInstanceOf(ContactWay::class, $result);
        $this->assertEquals('orderby_test_a', $result->getConfigId());
    }

    public function testAssociationQueryWithCorp(): void
    {
        $contactWay = $this->createValidContactWay('corp_association_test');
        $this->repository->save($contactWay);
        $corp = $contactWay->getCorp();

        // 类型断言确保是具体的实体而不是接口
        $this->assertInstanceOf(Corp::class, $corp);
        $corpId = $corp->getId();

        $queryBuilder = $this->repository->createQueryBuilder('c')
            ->innerJoin('c.corp', 'corp')
            ->where('corp.id = :corpId')
            ->setParameter('corpId', $corpId)
        ;

        /** @var ContactWay[] $results */
        $results = $queryBuilder->getQuery()->getResult();
        $this->assertGreaterThanOrEqual(1, count($results));
        $this->assertIsArray($results);
        $this->assertArrayHasKey(0, $results);
        $this->assertInstanceOf(ContactWay::class, $results[0]);
    }

    public function testAssociationQueryWithAgent(): void
    {
        $contactWay = $this->createValidContactWay('agent_association_test');
        $this->repository->save($contactWay);
        $agent = $contactWay->getAgent();

        // 类型断言确保是具体的实体而不是接口
        $this->assertInstanceOf(Agent::class, $agent);
        $agentId = $agent->getId();

        $queryBuilder = $this->repository->createQueryBuilder('c')
            ->innerJoin('c.agent', 'agent')
            ->where('agent.id = :agentId')
            ->setParameter('agentId', $agentId)
        ;

        /** @var ContactWay[] $results */
        $results = $queryBuilder->getQuery()->getResult();
        $this->assertGreaterThanOrEqual(1, count($results));
        $this->assertIsArray($results);
        $this->assertArrayHasKey(0, $results);
        $this->assertInstanceOf(ContactWay::class, $results[0]);
    }

    public function testCountByAssociationWithCorp(): void
    {
        $contactWay = $this->createValidContactWay('count_corp_association');
        $this->repository->save($contactWay);
        $corp = $contactWay->getCorp();

        // 类型断言确保是具体的实体而不是接口
        $this->assertInstanceOf(Corp::class, $corp);
        $corpId = $corp->getId();

        $queryBuilder = $this->repository->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->innerJoin('c.corp', 'corp')
            ->where('corp.id = :corpId')
            ->setParameter('corpId', $corpId)
        ;

        $count = $queryBuilder->getQuery()->getSingleScalarResult();
        $this->assertGreaterThanOrEqual(1, $count);
    }

    public function testCountByAssociationWithAgent(): void
    {
        $contactWay = $this->createValidContactWay('count_agent_association');
        $this->repository->save($contactWay);
        $agent = $contactWay->getAgent();

        // 类型断言确保是具体的实体而不是接口
        $this->assertInstanceOf(Agent::class, $agent);
        $agentId = $agent->getId();

        $queryBuilder = $this->repository->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->innerJoin('c.agent', 'agent')
            ->where('agent.id = :agentId')
            ->setParameter('agentId', $agentId)
        ;

        $count = $queryBuilder->getQuery()->getSingleScalarResult();
        $this->assertGreaterThanOrEqual(1, $count);
    }

    protected function createNewEntity(): object
    {
        $entity = new ContactWay();

        // 设置必需的基本字段
        $corp = new Corp();
        $corpUniqId = uniqid();
        $corp->setCorpId('test_corp_' . $corpUniqId);
        $corp->setName('测试企业_' . $corpUniqId);
        $corp->setCorpSecret('test_secret_123');

        $agent = new Agent();
        $agent->setAgentId('1000');
        $agent->setName('测试应用');
        $agent->setSecret('test_secret_123');
        $agent->setCorp($corp);

        // 持久化关联实体
        $entityManager = self::getService(EntityManagerInterface::class);
        $entityManager->persist($corp);
        $entityManager->persist($agent);
        $entityManager->flush();

        $entity->setCorp($corp);
        $entity->setAgent($agent);
        $entity->setConfigId('test_config_' . uniqid());
        $entity->setType(1);
        $entity->setScene(1);

        return $entity;
    }

    /**
     * @return ContactWayRepository
     */
    protected function getRepository(): ContactWayRepository
    {
        return $this->repository;
    }

    public function testFindOneByWithSorting(): void
    {
        $contactWay1 = $this->createValidContactWay('sort_test_z');
        $this->repository->save($contactWay1);

        $contactWay2 = $this->createValidContactWay('sort_test_a');
        $this->repository->save($contactWay2);

        $result = $this->repository->findOneBy(['type' => 1], ['configId' => 'ASC']);
        $this->assertInstanceOf(ContactWay::class, $result);
        $this->assertEquals('sort_test_a', $result->getConfigId());
    }

    public function testAssociationQueryWithMultipleConditions(): void
    {
        $contactWay = $this->createValidContactWay('multi_assoc_test');
        $contactWay->setType(2);
        $this->repository->save($contactWay);
        $agent = $contactWay->getAgent();
        $corp = $contactWay->getCorp();

        $this->assertInstanceOf(Agent::class, $agent);
        $this->assertInstanceOf(Corp::class, $corp);
        $agentId = $agent->getId();
        $corpId = $corp->getId();

        $queryBuilder = $this->repository->createQueryBuilder('c')
            ->innerJoin('c.agent', 'agent')
            ->innerJoin('c.corp', 'corp')
            ->where('agent.id = :agentId')
            ->andWhere('corp.id = :corpId')
            ->andWhere('c.type = :type')
            ->setParameter('agentId', $agentId)
            ->setParameter('corpId', $corpId)
            ->setParameter('type', 2)
        ;

        /** @var ContactWay[] $results */
        $results = $queryBuilder->getQuery()->getResult();
        $this->assertGreaterThanOrEqual(1, count($results));
        $this->assertIsArray($results);
        $this->assertArrayHasKey(0, $results);
        $this->assertInstanceOf(ContactWay::class, $results[0]);
    }

    /**
     * 针对Snowflake ID生成器提供专门的测试逻辑
     * 由于父类的测试方法是final的，我们创建一个新的测试方法来验证Snowflake ID的行为
     */
    public function testSnowflakeIdGenerationBehaviorWithSaveMethod(): void
    {
        $entity = $this->createNewEntity();
        $this->assertInstanceOf(ContactWay::class, $entity);
        $this->repository->save($entity, false);

        // ContactWay使用SnowflakeKeyAware trait，在persist时就会生成ID
        $id = self::getEntityManager()->getUnitOfWork()->getSingleIdentifierValue($entity);

        // 对于Snowflake ID，在persist时就应该有ID
        $this->assertNotEmpty($id, 'Snowflake ID应该在persist时就生成');
        $this->assertTrue(self::getEntityManager()->contains($entity), '实体应该已被EntityManager管理');

        // 验证ID格式（Snowflake ID可能是字符串或整数）
        $this->assertTrue(is_string($id) || is_int($id), 'Snowflake ID应该是字符串或整数格式');
        if (is_string($id)) {
            $this->assertMatchesRegularExpression('/^\d+$/', $id, 'Snowflake ID字符串应该是数字格式');
            $this->assertGreaterThan(0, (int) $id, 'Snowflake ID应该大于0');
        } else {
            $this->assertGreaterThan(0, $id, 'Snowflake ID应该大于0');
        }

        // 手动flush
        self::getEntityManager()->flush();

        // flush后ID应该保持不变
        $finalId = self::getEntityManager()->getUnitOfWork()->getSingleIdentifierValue($entity);
        $this->assertEquals($id, $finalId, 'Snowflake ID在persist和flush前后应该保持一致');
        $this->assertNotNull($entity->getId(), 'flush后实体应该仍有有效的ID');
    }
}
