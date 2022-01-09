<?php

namespace Spatie\Health\ResultStores;

use Illuminate\Support\Collection;

class ResultStores
{
    /** @return Collection<int, ResultStore> */
    public static function createFromConfig(): Collection
    {
        $configValues = config('health.result_stores');

        return collect($configValues)
            ->keyBy(fn ($value, $key) => is_array($value) ? $key : $value)
            ->map(fn ($value) => is_array($value) ? $value : [])
            ->map(function (array $parameters, string $className): ResultStore {
                return app($className, $parameters);
            });
    }
}
