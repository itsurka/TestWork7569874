<?php

namespace App\Repository;

use App\Entity\Car;
use App\Enum\EntityStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function search(array $filter, array $params = [])
    {
        $params = array_merge([
            'relations' => [],
        ], $params);

        $qb = $this->createQueryBuilder('c');
        foreach ($filter as $param => $value) {
            switch ($param) {
                case 'owner':
                    $qb->andWhere('c.owner = :owner')->setParameter('owner', $value);
                    break;
            }
        }

        foreach ($params['relations'] as $relation) {
            $qb->join($relation['join'], $relation['alias'], $relation['condition_type']);
        }

        return $qb;
    }

    /**
     * @param array $params
     * @param bool $forCount
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getList(array $params = [], bool $forCount = false)
    {
        $params = array_merge([
            'published' => null
        ], $params);

        $qb = $this->createQueryBuilder('c');

        if ($params['published'] !== null) {
            // todo
        }

        if (!$forCount) {
            $qb->orderBy('c.created_at', 'desc');
        }

        return $qb;
    }

    public function getPublishedById(int $id): ?Car
    {
        return $this->findOneBy([
            'id' => $id,
            'status' => EntityStatus::STATUS_PUBLISHED,
        ]);
    }

    public function save(Car $car)
    {
        $this->getEntityManager()->persist($car);
        $this->getEntityManager()->flush();
    }

    // /**
    //  * @return Car[] Returns an array of Car objects
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
    public function findOneBySomeField($value): ?Car
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
