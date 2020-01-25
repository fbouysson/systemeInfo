<?php

namespace App\Repository;

use App\Entity\UserUCO;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;


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


    /**
     * @param $idSalon
     * @return array
     * @throws DBALException
     */
    public function getAllUserInSalon($idSalon)
    {
        $sql = "SELECT u.id_user, u.login_username from useruco u
left join affectation_salon a on u.id_user = a.id_user
Where a.id_salon = $idSalon";

        $conn = $this->getEntityManager()
            ->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * @param $idSalon
     * @return array
     * @throws DBALException
     */
    public function getAllUserNotInSalon($idSalon)
    {
        $sql =  "SELECT u.id_user, u.login_username from useruco u where u.id_user not in (SELECT u.id_user from useruco u left join affectation_salon a on u.id_user = a.id_user Where a.id_salon = $idSalon)";

        $conn = $this->getEntityManager()
            ->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
