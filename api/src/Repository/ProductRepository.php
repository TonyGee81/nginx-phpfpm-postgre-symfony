<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function findByPagination(string $pageSize, int $pageNumber = 1): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.price', 'p.description', 'p.code', 'supplier.name AS supplierName')
            ->orderBy('p.description', 'ASC')
            ->leftJoin('p.supplier', 'supplier', 'WITH', 'p.supplier = supplier.id')
            ->setMaxResults($pageSize)
            ->setFirstResult($pageSize * ($pageNumber - 1))
            ->getQuery()
            ->getResult();
    }

    public function findByCode(string $search): array
    {
        return $this->createQueryBuilder('p')
            ->orWhere('p.code LIKE :code')
            ->orWhere('p.description LIKE :description')
            ->setParameter('code', '%' . $search . '%')
            ->setParameter('description', '%' . $search . '%')
            ->getQuery()
            ->getResult();
    }


}
