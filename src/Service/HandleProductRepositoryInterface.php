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

    public function addOrder(QueryBuilder $query, array $params): void
    {
        $order = $this->addOrderFields($params);
        if ($order) {
            $query->orderBy($order, "ASC")
                ->addOrderBy('p.price', 'ASC')
                ->addOrderBy('p.name', 'ASC');
        }
    }

    public function addOrderFields(array $params): ?string
    {
        if (
            !$params['minHeight'] && !$params['maxHeight']
            && !$params['minWidth'] && !$params['maxWidth']
            && !$params['minDepth'] && !$params['maxDepth']
        ) {
            return null;
        }

        $dimensionsMin = $this->completeParameters($params)['dimensionsMin'];
        $dimensionsMax = $this->completeParameters($params)['dimensionsMax'];

        $criteriasMin = $this->completeParameters($params)['criteriasMin'];
        $criteriasMax = $this->completeParameters($params)['criteriasMax'];

        $min = $this->defineExpressions($dimensionsMin, $criteriasMin);
        $max = $this->defineExpressions($dimensionsMax, $criteriasMax);

        return $this->defineOrderStatement($criteriasMin, $min, $criteriasMax, $max);
    }

    private function completeParameters(array $params): array
    {
        $dimensionsMin = [];
        $dimensionsMax = [];

        $criteriasMin = 0;
        $criteriasMax = 0;

        if ($params['minHeight']) {
            $dimensionsMin[] = 'p.height';
            $criteriasMin += $params['minHeight'];
        }
        if ($params['maxHeight']) {
            $dimensionsMax[] = 'p.height';
            $criteriasMax += $params['maxHeight'];
        }
        if ($params['minWidth']) {
            $dimensionsMin[] = 'p.width';
            $criteriasMin += $params['minWidth'];
        }
        if ($params['maxWidth']) {
            $dimensionsMax[] = 'p.width';
            $criteriasMax += $params['maxWidth'];
        }
        if ($params['minDepth']) {
            $dimensionsMin[] = 'p.depth';
            $criteriasMin += $params['minDepth'];
        }
        if ($params['maxDepth']) {
            $dimensionsMax[] = 'p.depth';
            $criteriasMax += $params['maxDepth'];
        }

        return [
            'dimensionsMin' => $dimensionsMin,
            'dimensionsMax' => $dimensionsMax,
            'criteriasMin' => $criteriasMin,
            'criteriasMax' => $criteriasMax
        ];
    }

    private function defineExpressions(array $dimensions, int $criterias): string
    {
        $select = implode(" + ", $dimensions);

        return "abs(($select) - $criterias)";
    }

    private function defineOrderStatement(int $criteriasMin, string $min, int $criteriasMax, string $max): string
    {
        $order = '';

        if ($criteriasMin && $criteriasMax) {
            $order .= "least($min, $max)";
        } elseif ($criteriasMin) {
            $order .= $min;
        } elseif ($criteriasMax) {
            $order .= $max;
        }

        return $order;
    }
}
