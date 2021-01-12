<?php

namespace App\Repository;

use App\Entity\CarBrand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CarBrand|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarBrand|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarBrand[]    findAll()
 * @method CarBrand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarBrandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarBrand::class);
    }

    // /**
    //  * @return CarMake[] Returns an array of CarMake objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CarMake
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
