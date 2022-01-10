<?php

namespace App\Service;

class ProductRepositoryManager
{
    public function getDimensionCriteria(string $criteria): string
    {
        return $criteria == 'min' ? '>' : '<';
    }
}
