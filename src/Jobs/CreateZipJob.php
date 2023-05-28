<?php

namespace VSolutionDev\LaravelZipper\Jobs;

use VSolutionDev\LaravelZipper\Files\RemoteTemporaryFile;

class CreateZipJob extends Job
{

    protected $temporary;

    public function __construct(RemoteTemporaryFile $temporary)
    {
        $this->temporary = $temporary;
    }

    public function handle()
    {
        \Zipper::create($this->temporary->getLocalPath())->close();

        $this->temporary->uploadAndDelete();
    }
}

