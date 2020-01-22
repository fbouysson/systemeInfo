<?php

namespace App\Repository;

use App\Entity\UCOUser;
use App\Entity\UserUCO;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UserUCO|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserUCO|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserUCO[]    findAll()
 * @method UserUCO[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserUCORepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserUCO::class);
    }

    // /**
    //  * @return UCOUser[] Returns an array of UCOUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UCOUser
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
