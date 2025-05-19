<?php

namespace App\Schema\Base;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractConvertData implements ConvertDataInterface
{
    /**
     * Convert collection of models
     *
     * @param \Illuminate\Support\Collection|array $items
     * @return array
     */
    public function convertCollection($items): array
    {
        $result = [];

        foreach ($items as $key => $item) {
            $result[$key] = $this->convertData($item);
        }

        return $result;
    }

    /**
     * Convert single model item
     *
     * @param Model $items
     * @return array
     */
    abstract public function convertData(Model $model): array;
}
