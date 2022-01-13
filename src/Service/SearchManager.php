<?php

namespace App\Service;

use App\Entity\Product;

class SearchManager
{
    public function sortProducts(array $products, array $params): array
    {
        if (!$products) {
            return [];
        }

        if (
            !$params['minHeight'] && !$params['maxHeight']
            && !$params['minWidth'] && !$params['maxWidth']
            && !$params['minDepth'] && !$params['maxDepth']
        ) {
            return $products;
        }

        $sortedProducts = [];
        foreach ($products as $product) {
            $sortedProducts[$this->addWeight($product, $params)] = $product;
        }
        ksort($sortedProducts);

        return $sortedProducts;
    }

    private function addWeight(Product $product, array $params): string
    {
        $height = strval($product->getHeight());
        $width = strval($product->getWidth());
        $depth = strval($product->getDepth());

        $criterias = [
            $height => [
                $params['minHeight'],
                $params['maxHeight'],
            ],
            $width => [
                $params['minWidth'],
                $params['maxWidth'],
            ],
            $depth => [
                $params['minDepth'],
                $params['maxDepth'],
            ]
        ];

        $weight = strval("0.000" . $product->getId());

        foreach ($criterias as $dimension => $criteria) {
            if ($criteria[0] && $criteria[1]) {
                $weight += min(abs($dimension - $criteria[0]), abs($dimension - $criteria[1]));
            } elseif ($criteria[0]) {
                $weight += abs($dimension - $criteria[0]);
            } elseif ($criteria[1]) {
                $weight += abs($dimension - $criteria[1]);
            }
        }



        return strval($weight);
    }
}
