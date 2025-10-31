<?php

declare(strict_types=1);

namespace WechatWorkContactWayBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatWorkContactWayBundle\Entity\ContactWay;

/**
 * @extends ServiceEntityRepository<ContactWay>
 */
#[Autoconfigure(public: true)]
#[AsRepository(entityClass: ContactWay::class)]
class ContactWayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactWay::class);
    }

    public function save(ContactWay $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ContactWay $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
