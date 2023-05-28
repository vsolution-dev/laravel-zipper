<?php

namespace VSolutionDev\LaravelZipper\Jobs;

use VSolutionDev\LaravelZipper\Files\RemoteTemporaryFile;

class AppendFileJob extends Job
{

    protected $temporary;
    protected $files = [];

    public function __construct(RemoteTemporaryFile $temporary, $files)
    {
        $this->files = $files;
        $this->temporary = $temporary;
    }

    public function handle()
    {
        $this->temporary->download();

        $archive = \Zipper::open($this->temporary->getLocalPath());

        foreach ($this->files as $file) {
            $archive->add($file['path'], $file['name']);
        }

        $archive->close();

        $this->temporary->uploadAndDelete();
    }
}

