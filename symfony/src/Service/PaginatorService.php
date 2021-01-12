<?php


namespace App\Service;


use App\Doctrine\Paginator;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

class PaginatorService
{
    /**
     * @param QueryBuilder $queryBuilderData
     * @param QueryBuilder $queryBuilderCount
     * @param Request $request
     * @param int $pageSize
     * @return Paginator
     */
    public function paginate(
        QueryBuilder $queryBuilderData,
        QueryBuilder $queryBuilderCount,
        Request $request, int $pageSize = 20
    ): Paginator
    {
        $currentPage = $request->query->getInt('p') ?: 1;
        $paginator = new Paginator($queryBuilderData, $queryBuilderCount);
        $paginator
            ->getQuery()
            ->setFirstResult($pageSize * ($currentPage - 1))
            ->setMaxResults($pageSize);

        return $paginator;
    }
}