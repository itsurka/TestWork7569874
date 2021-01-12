<?php


namespace App\Doctrine;


use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class Paginator extends \Doctrine\ORM\Tools\Pagination\Paginator
{
    /** @var QueryBuilder */
    private $queryBuilderCount;

    /**
     * Paginator constructor.
     * @param QueryBuilder $queryBuilderData
     * @param QueryBuilder $queryBuilderCount
     * @param bool $fetchJoinCollection
     */
    public function __construct(QueryBuilder $queryBuilderData, QueryBuilder $queryBuilderCount, $fetchJoinCollection = true)
    {
        parent::__construct($queryBuilderData, $fetchJoinCollection);
        $this->queryBuilderCount = $queryBuilderCount;
    }

    public function getPagesCount(): int
    {
        return ceil($this->queryBuilderCount->select('count(c.id)')->getQuery()->getSingleScalarResult() / $this->getQuery()->getMaxResults());
    }
}