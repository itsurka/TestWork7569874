<?php

namespace App\Repository;

use App\Entity\CarBrandModel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CarBrandModel|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarBrandModel|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarBrandModel[]    findAll()
 * @method CarBrandModel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarBrandModelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarBrandModel::class);
    }

    // /**
    //  * @return CarBrandModel[] Returns an array of CarModel objects
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
    public function findOneBySomeField($value): ?CarModel
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
