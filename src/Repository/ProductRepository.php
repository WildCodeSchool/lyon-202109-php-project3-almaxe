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



        if ($parameters['height']) {
            $height = $parameters['height'];
            $query->andWhere('p.height <= :height')
                ->setParameter('height', $height);
        }

        if ($parameters['width']) {
            $width = $parameters['width'];
            $query->andWhere('p.width <= :width')
                ->setParameter('width', $width);
        }

        if ($parameters['depth']) {
            $depth = $parameters['depth'];
            $query->andWhere('p.depth <= :depth')
                ->setParameter('depth', $depth);
        }

        if ($parameters['words'] != "all") {
            $words = $parameters['words'];
            $words = explode('%20', $words);
            $condition = "";
            $first = true;
            foreach ($words as $word) {
                if ($first) {
                    $condition .= "p.name LIKE '%$word%' ";
                    $first = false;
                }
                $condition .= "OR p.name LIKE '%$word%' ";
            }
            $query->andWhere($condition);
        }

        return (array) $query->getQuery()->getResult();
    }
}
