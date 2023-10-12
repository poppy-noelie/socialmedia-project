<?php

namespace App\Repository;

use App\Entity\BaseEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BaseEntity>
 *
 * @method BaseEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseEntity[]    findAll()
 * @method BaseEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseEntity::class);
    }

    public function save(BaseEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BaseEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
