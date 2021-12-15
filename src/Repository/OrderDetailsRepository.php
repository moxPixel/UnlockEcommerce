<?php

namespace App\Repository;

use App\Entity\OrderDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderDetails[]    findAll()
 * @method OrderDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderDetails::class);
    }

    // /**
    //  * @return OrderDetails[] Returns an array of OrderDetails objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
 /**
    * @return Order[] Returns an array of Order objects
    */
    public function findByOrderIsPaidForUser($isPaid,$user)
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.myOrder', 'od')
            ->addSelect('od')
            ->where('od.isPaid = :isPaid')
            ->andWhere('od.user = :user')
            ->andWhere('od.id = o.myOrder')
            ->setParameter('isPaid', $isPaid)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    
        /**
     * Calcule chiffre d'affaire pour l anné en cours
     * @return int|mixed
     * @throws Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function findByTotalSals($isPaid): array
    {
        $date = new \DateTime();
        return $this->createQueryBuilder('o')
            ->innerJoin('o.myOrder', 'od')
            ->addSelect('SUM(o.total) * COUNT(o.total) as total')
            ->where('od.isPaid = :isPaid')
            ->andWhere('YEAR(od.createdAt) = YEAR(:dateNow)')
            ->setParameter('dateNow', $date->format('Y-m-d 00:00:00'))
            ->setParameter('isPaid', $isPaid)
            ->getQuery()
            ->getResult();
    }

 
    /*
    public function findOneBySomeField($value): ?OrderDetails
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
