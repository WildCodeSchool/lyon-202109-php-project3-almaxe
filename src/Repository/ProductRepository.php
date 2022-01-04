<?php

namespace App\Repository;

use App\Entity\Product;
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

        $criteria = $parameters['criteria'] == 'min' ? '>' : '<';

        if ($parameters['category']) {
            $category = $parameters['category'];
            $query->andWhere('p.category = :category')
                ->setParameter('category', $category);
        }

        if ($parameters['height']) {
            $height = $parameters['height'];
            $query->andWhere('p.height ' . $criteria . '= :height')
                ->setParameter('height', $height);
        }

        if ($parameters['width']) {
            $width = $parameters['width'];
            $query->andWhere('p.width ' . $criteria . '= :width')
                ->setParameter('width', $width);
        }

        if ($parameters['depth']) {
            $depth = $parameters['depth'];
            $query->andWhere('p.depth ' . $criteria . '= :depth')
                ->setParameter('depth', $depth);
        }

        return (array) $query->getQuery()->getResult();
    }
}
