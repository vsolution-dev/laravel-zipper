<?php

namespace VSolutionDev\LaravelZipper\Jobs;


use VSolutionDev\LaravelZipper\Files\RemoteTemporaryFile;

class ReleaseZipJob extends Job
{
    protected $path;

    /**
     * @var RemoteTemporaryFile
     */
    protected $temporary;

    public function __construct($temporary, $path)
    {
        $this->path = $path;
        $this->temporary = $temporary;
    }

    public function handle()
    {
        $this->temporary->moveTo($this->path);
    }
}

