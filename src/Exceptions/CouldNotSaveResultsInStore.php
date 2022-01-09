<?php

namespace Spatie\Health\Exceptions;

use Exception;
use Spatie\Health\Models\HealthCheckResultHistoryItem;
use Spatie\Health\ResultStores\ResultStore;

class CouldNotSaveResultsInStore extends Exception
{
    public static function make(ResultStore $store, Exception $exception): self
    {
        $storeClass = get_class($store);

        return new self(
            "Could not save results in the `{$storeClass}` did not complete. An exception was thrown with this message: `{$exception->getMessage()}`",
            0,
            $exception
        );
    }

    public static function doesNotExtendHealthCheckResultHistoryItem($invalidValue): self
    {
        $className = HealthCheckResultHistoryItem::class;

        return new self(
            "You tried to register an invalid HealthCheckResultHistoryItem model: `{$invalidValue}`. A valid model should extend `{$className}`"
        );
    }
}
