<?php

namespace App\Repository;

use App\Entity\Logs;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method Logs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Logs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Logs[]    findAll()
 * @method Logs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Logs::class);
    }

    /**
     * @param $action
     * String expliquant l'action réalisé
     * @param $salon
     * Objet salon lié au log (null si lié a aucun salon)
     * @param null $satut
     * (0 : connection, 1 : deconnexion, null : sinon)
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createLog($action,$salon = null,$satut = null){

        $logs = new Logs();
        $logs->setAction($action)
            ->setSalon($salon)
            ->setStatut($satut)
            ->setDatetime(new DateTime());

        $this->getEntityManager()->persist($logs);

        $this->getEntityManager()->flush();

    }

    // /**
    //  * @return Logs[] Returns an array of Logs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Logs
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
