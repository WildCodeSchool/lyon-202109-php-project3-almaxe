<?php

namespace App\Service;

class HandleProductSearch
{
    public function checkParameters(array $parameters): bool
    {
        $params = [[
            'min' => $parameters['minHeight'],
            'max' => $parameters['maxHeight'],
        ], [
            'min' => $parameters['minWidth'],
            'max' => $parameters['maxWidth'],
        ], [
            'min' => $parameters['minDepth'],
            'max' => $parameters['maxDepth'],
        ]];

        foreach ($params as $param) {
            if ($param['min'] && $param['max']) {
                if ($param['min'] >= $param['max']) {
                    return false;
                }
            }
        }

        return true;
    }
}
