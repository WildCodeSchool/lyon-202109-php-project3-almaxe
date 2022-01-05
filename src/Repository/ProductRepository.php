<?php

namespace App\Repository;

use App\Entity\Product;
use App\Service\HandleProductRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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

        $query = $this->createQueryBuilder('p');

        $service = new HandleProductRepositoryInterface();

        $criteriaWidth = $service->getDimensionCriteria($parameters['criteriaWidth']);
        $criteriaDepth = $service->getDimensionCriteria($parameters['criteriaDepth']);
        $criteriaHeight = $service->getDimensionCriteria($parameters['criteriaHeight']);

        if ($parameters['category']) {
            $category = $parameters['category'];
            $query->andWhere('p.category = :category')
                ->setParameter('category', $category);
        }

        if ($parameters['height']) {
            $height = $parameters['height'];
            $query->andWhere('p.height ' . $criteriaHeight . '= :height')
                ->setParameter('height', $height);
        }

        if ($parameters['width']) {
            $width = $parameters['width'];
            $query->andWhere('p.width ' . $criteriaWidth . '= :width')
                ->setParameter('width', $width);
        }

        if ($parameters['depth']) {
            $depth = $parameters['depth'];
            $query->andWhere('p.depth ' . $criteriaDepth . '= :depth')
                ->setParameter('depth', $depth);
        }

        if ($parameters['price']) {
            $price = $parameters['price'];
            $query->andWhere('p.price <= :price')
                ->setParameter('price', $price);
        }

        return (array) $query->getQuery()->getResult();
    }
}
