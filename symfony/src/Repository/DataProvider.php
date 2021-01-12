<?php

declare(strict_types=1);

namespace App\Repository;


use Doctrine\ORM\QueryBuilder;

class DataProvider
{
    const DEFAULT_PAGE_SIZE = 20;

    /** @var QueryBuilder */
    private $queryBuilder;

    /** @var int|null */
    private $pageSize;

    /** @var mixed|null */
    private $items;

    /** @var int|null */
    private $itemCount;

    /** @var int */
    private $currentPage;

    /** @var int|null */
    private $pageCount;

    /** @var array|null|bool */
    private $orderBy = null;

    private $defaultOrderBy = ['created_at' => 'desc'];

    public function __construct(
        QueryBuilder $queryBuilder,
        int $currentPage,
        int $pageSize = self::DEFAULT_PAGE_SIZE
    ) {
        $this->queryBuilder = $queryBuilder;
        $this->currentPage = $currentPage;
        $this->pageSize = $pageSize;
    }

    public function getItems()
    {
        if ($this->itemCount === null) {
            $this->prepareItemCount();
            $this->prepareItems();
        }
        return $this->items;
    }

    public function getPageCount(): int
    {
        if ($this->itemCount === null) {
            $this->prepareItemCount();
        }
        return $this->pageCount;
    }

    public function setOrderBy($orderBy): self
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    private function prepareItemCount(): void
    {
        $alias = $this->getMainAlias();
        $qb = clone $this->queryBuilder;
        $this->itemCount = $qb->select('count(' . $alias . '.id)')->getQuery()->getSingleScalarResult();
        $this->pageCount = $this->itemCount > 0 ? (int)ceil($this->itemCount / $this->pageSize) : 0;
    }

    private function prepareItems(): void
    {
        if ($this->itemCount === 0) {
            $this->items = [];
            return;
        }

        $offset = ($this->currentPage - 1) * $this->pageSize;

        $qb = clone $this->queryBuilder;
        foreach ($this->getOrder() as $sort => $order) {
            $qb->addOrderBy($sort, $order);
        }

        $this->items = $qb->setMaxResults($this->pageSize)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    private function getMainAlias(): ?string
    {
        $aliases =  $this->queryBuilder->getRootAliases();
        return $aliases ? reset($aliases) : null;
    }

    private function getOrder(): array
    {
        if ($this->orderBy === false) {
            return [];
        }
        if ($this->orderBy === null) {
            $orderBy = $this->defaultOrderBy;
            $sort = key($orderBy);
            $order = reset($orderBy);
            $orderBy = [
                $this->getMainAlias() . '.' . $sort => $order
            ];

            return $orderBy;
        }

        return $this->orderBy;
    }
}
