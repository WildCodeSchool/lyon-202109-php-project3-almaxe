<?php

namespace App\Service;

class HandleProductRepositoryInterface
{
    public function getDimensionCriteria(string $criteria): string
    {
        return $criteria == 'min' ? '>' : '<';
    }
}
