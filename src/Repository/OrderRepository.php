<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
    * @return Order[] Returns an array of Order objects
    */
    public function findByOrderIsPaid($isPaid)
    {
        return $this->createQueryBuilder('o')
            ->addSelect('MONTH(o.createdAt) as month ,  COUNT(o.createdAt)  as total')
            ->where('o.isPaid = :isPaid')
            ->setParameter('isPaid', $isPaid)
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    
   

       /**
     * Calcule le nombre de dates par mois pour l'annéé en cour
     * @return int|mixed
     * @throws Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function findOrderByMonth(): array
    {
        $date = new \DateTime();
        return $this->createQueryBuilder('o')
            ->addSelect('MONTH(o.createdAt) as month ,  COUNT(o.createdAt)  as total')
            ->andWhere('YEAR(o.createdAt) = YEAR(:dateNow)')
            ->setParameter('dateNow', $date->format('Y-m-d 00:00:00'))
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->getQuery()
            ->getResult();
    }
    

    /*
    public function findOneBySomeField($value): ?Order
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
