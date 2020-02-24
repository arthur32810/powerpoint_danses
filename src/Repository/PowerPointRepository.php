<?php

namespace App\Repository;

use App\Entity\PowerPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PowerPoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method PowerPoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method PowerPoint[]    findAll()
 * @method PowerPoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PowerPointRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PowerPoint::class);
    }

    // /**
    //  * @return PowerPoint[] Returns an array of PowerPoint objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PowerPoint
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
