<?php

namespace App\Repository;

use App\Entity\Logs;
use App\Entity\Messages;
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

    /**
     * @param $idSalon
     * @return array
     * @throws DBALException
     */
    public function getAllUserInSalon($idSalon)
    {
        $sql = "SELECT u.id, u.username from useruco u
left join affectation_salon a on u.id = a.id_user
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
        $sql =  "SELECT u.id, u.username from useruco u where u.id not in (SELECT u.id from useruco u left join affectation_salon a on u.id = a.id_user Where a.id_salon = $idSalon)";

        $conn = $this->getEntityManager()
            ->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * @param $idSalon
     * @param $username
     * @return int
     * @throws DBALException
     */
    public function getLastMessageSeen($idSalon, $username)
    {
        $remindMessage = 0;

        $lastLogSalon = $this->getEntityManager()->getRepository(Logs::class)->findOneBy(["action" => $username, 'salon' => $idSalon, "statut" => 1],["datetime" => "desc"]);

        if ($lastLogSalon != null){
            $dateDeco = $lastLogSalon->getDatetime()->format("Y-m-d H:i:s");
            $sql =  "SELECT id_messages from messages where id_salon = '$idSalon' and date > '$dateDeco'";

            $conn = $this->getEntityManager()
                ->getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $tmp = $stmt->fetch();
            if(!$tmp){
                $remindMessage = null;
            }else{
                $remindMessage = $tmp["id_messages"];
            }
        }

        return $remindMessage;
    }

}
