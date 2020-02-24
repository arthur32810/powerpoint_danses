<?php

namespace App\Repository;

use App\Entity\Danse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Danse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Danse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Danse[]    findAll()
 * @method Danse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DanseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Danse::class);
    }

    // /**
    //  * @return Danse[] Returns an array of Danse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Danse
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
