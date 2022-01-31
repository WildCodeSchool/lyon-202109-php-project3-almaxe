<?php

namespace App\Repository;

use App\Entity\Product;
use App\Service\HandleProductRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Select;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function searchProduct(array $parameters): array
    {

        $query = $this->createQueryBuilder('p')
            ->addSelect('avg(r.rating) as meanRating')
            ->join('p.reviews', 'r')
            ->groupBy('p')
            ;

        $service = new HandleProductRepositoryInterface();

        if ($parameters['category']) {
            $category = $parameters['category'];
            $query->andWhere('p.category = :category')
                ->setParameter('category', $category);
        }

        if ($parameters['minHeight'] || $parameters['maxHeight']) {
            $service->setSubQuery($query, 'p.height', $parameters['minHeight'], $parameters['maxHeight']);
        }

        if ($parameters['minWidth'] || $parameters['maxWidth']) {
            $service->setSubQuery($query, 'p.width', $parameters['minWidth'], $parameters['maxWidth']);
        }

        if ($parameters['minDepth'] || $parameters['maxDepth']) {
            $service->setSubQuery($query, 'p.depth', $parameters['minDepth'], $parameters['maxDepth']);
        }

        if ($parameters['price']) {
            $price = $parameters['price'];
            $query->andWhere('p.price <= :price')
                ->setParameter('price', $price);
        }
        $query->setFirstResult(($parameters['page'] - 1) * 20);
        $query->setMaxResults(20);

        $service->addOrder($query, $parameters);

        return (array) $query->getQuery()->getResult();
    }

    public function countSearchProduct(array $parameters): int
    {

        $query = $this->createQueryBuilder('p');
        $query->select('count(p.id)');

        $service = new HandleProductRepositoryInterface();



        if ($parameters['category']) {
            $category = $parameters['category'];
            $query->andWhere('p.category = :category')
                ->setParameter('category', $category);
        }

        if ($parameters['minDepth'] || $parameters['maxDepth']) {
            $service->setSubQuery($query, 'p.depth', $parameters['minDepth'], $parameters['maxDepth']);
        }

        if ($parameters['minHeight'] || $parameters['maxHeight']) {
            $service->setSubQuery($query, 'p.height', $parameters['minHeight'], $parameters['maxHeight']);
        }

        if ($parameters['minWidth'] || $parameters['maxWidth']) {
            $service->setSubQuery($query, 'p.width', $parameters['minWidth'], $parameters['maxWidth']);
        }

        if ($parameters['price']) {
            $price = $parameters['price'];
            $query->andWhere('p.price <= :price')
                ->setParameter('price', $price);
        }

        return intval($query->getQuery()->getSingleScalarResult());
    }
}
