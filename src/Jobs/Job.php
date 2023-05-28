<?php

namespace VSolutionDev\LaravelZipper\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class Job implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        SerializesModels,
        Queueable;

    public function chain($chain)
    {
        collect($chain)->each(function ($job) {
            $this->chained []= $this->serializeJob($job);
        });

        return $this;
    }
}
