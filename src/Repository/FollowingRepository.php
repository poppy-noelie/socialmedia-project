<?php

namespace App\Repository;

use App\Entity\Following;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Following>
 *
 * @method Following|null find($id, $lockMode = null, $lockVersion = null)
 * @method Following|null findOneBy(array $criteria, array $orderBy = null)
 * @method Following[]    findAll()
 * @method Following[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FollowingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Following::class);
        $this->em = $em;
    }

    public function save(Following $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Following $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function isFollowing(int $user_id, int $target_user_id): bool
    {
        return  $this->createQueryBuilder('f')
            ->select('COUNT(f)')
            ->where('f.user = :user_id')
            ->andWhere('f.target_user = :target_user_id')
            ->setParameter('user_id', $user_id)
            ->setParameter('target_user_id', $target_user_id)
            ->getQuery()
            ->getSingleScalarResult();

    }

//    /**
//     * @return Following[] Returns an array of Following objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Following
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
