<?php

namespace Spatie\Health\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Spatie\Health\Enums\Status;
use Spatie\Health\ResultStores\ResultStore;
use function Termwind\render;

class ListHealthChecksCommand extends Command
{
    public $signature = 'health:list {--fresh} {--do-not-store-results} {--no-notification}';

    public $description = 'List all health checks';

    public function handle(): int
    {
        if ($this->option('fresh')) {
            $parameters = [];
            if ($this->option('do-not-store-results')) {
                $parameters[] = '--do-not-store-results';
            }
            if ($this->option('no-notification')) {
                $parameters[] = '--no-notification';
            }

            Artisan::call(RunHealthChecksCommand::class, $parameters);
        }

        $resultStore = app(ResultStore::class);

        $checkResults = $resultStore->latestResults();

        render(view('health::list-cli', [
            'lastRanAt' => new Carbon($checkResults ? $checkResults->finishedAt : null),
            'checkResults' => $checkResults,
            'color' => fn (string $status) => $this->getBackgroundColor($status),
        ]));

        return self::SUCCESS;
    }

    protected function getBackgroundColor(string $status): string
    {
        $status = Status::from($status);

        switch ($status) {
            case Status::ok():
                return 'bg-green-800';
            case Status::warning():
                return 'bg-yellow-800';
            case Status::skipped():
                return 'bg-blue-800';
            case Status::failed():
            case Status::crashed():
                return 'bg-red-800';
        }

        return '';
    }
}
