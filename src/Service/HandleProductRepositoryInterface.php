<?php

namespace App\Service;

use Doctrine\ORM\QueryBuilder;

class HandleProductRepositoryInterface
{
    public function setSubQuery(QueryBuilder $query, string $criteria, ?int $min, ?int $max): void
    {
        $placeholder = explode('.', $criteria)[1];

        if ($min) {
            $query->andWhere("$criteria >= :min$placeholder")
                ->setParameter("min$placeholder", $min);
        }
        if ($max) {
            $query->andWhere("$criteria <= :max$placeholder")
                ->setParameter("max$placeholder", $max);
        }
    }
}
