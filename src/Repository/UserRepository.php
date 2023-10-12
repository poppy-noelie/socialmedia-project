<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


//    public function isFollowing(int $user_id, int $target_user_id): bool
//    {
//        return $this->createQueryBuilder('u')
//            ->select('COUNT(f)')
//            ->join('u.followings', 'f')
//            ->where('f.user = :user_id')
//            ->andWhere('f.target_user = :target_user_id')
//            ->setParameter('user_id', $user_id)
//            ->setParameter('target_user_id', $target_user_id)
//            ->getQuery()
//            ->getSingleScalarResult();
//    }


    public function getFollowersCount(int $user_id)
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(f.target_user)')
            ->join('u.followings', 'f')
            ->where('f.target_user = :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function getFollowingsCount(int $user_id)
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(f.user)')
            ->join('u.followings', 'f')
            ->where('f.user = :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function getFollowers(int $user_id)
    {
        return $this->createQueryBuilder('u')
            ->join('u.followings', 'f')
            ->where('f.target_user = :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getResult();
    }


    public function getFollowings(int $user_id)
    {
        return $this->createQueryBuilder('u')
            ->select('identity(f.target_user)')
            ->join('u.followings', 'f')
            ->where('f.user = :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getResult();
    }

    public function searchBy(string $query)
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.username LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    }
}
