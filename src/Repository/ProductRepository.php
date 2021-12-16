<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function findAllLesserThanDimensions(int $height, int $width, int $depth)
    {
        $qb = $this->createQueryBuilder('p');

        if ($height) {
            $qb->where('p.height < :height')
                ->setParameter('height', $height);
        }

        if ($width) {
            if ($height) {
                $qb->andWhere('p.width < :width');
            } else {
                $qb->where('p.width < :width');
            }

            $qb->setParameter('width', $width);
        }

        if ($depth) {
            if ($height || $width) {
                $qb->andWhere('p.depth < :depth');
            } else {
                $qb->where('p.depth < :depth');
            }
            $qb->setParameter('depth', $depth);
        }

        if ($height) {
            $qb->orderBy('p.height', 'DESC');
        } elseif ($width) {
            $qb->orderBy('p.width', 'DESC');
        } elseif ($depth) {
            $qb->orderBy('p.depth', 'DESC');
        }

        $query = $qb->getQuery();

        return $query->execute();
    }
}
