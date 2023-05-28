<?php

namespace VSolutionDev\LaravelZipper;

use Illuminate\Foundation\Bus\PendingDispatch;
use VSolutionDev\LaravelZipper\Files\RemoteTemporaryFile;
use VSolutionDev\LaravelZipper\Jobs\AppendFileJob;
use VSolutionDev\LaravelZipper\Jobs\CreateZipJob;
use VSolutionDev\LaravelZipper\Jobs\ReleaseZipJob;

class Zipper
{

    protected $files = [];
    protected $archive;

    public function create($name, $flags = \ZipArchive::CREATE | \ZipArchive::OVERWRITE)
    {
        return tap($this->open($name, $flags), function () {
            $this->archive->addEmptyDir('.');
        });
    }

    public function open($path, $flags = null)
    {
        if ($this->archive) {
            $this->close();
        }

        $archive = new \ZipArchive;
        if ( ! $archive->open($path, $flags)) {
            throw new \Exception('압축 생성을 실패했습니다.');
        }

        $this->archive = $archive;

        return $this;
    }

    public function extract($path)
    {
        if ( ! $this->archive) {
            throw new \Exception('잘못된 접근입니다.');
        }

        $this->archive->extractTo($path);

        return $this;
    }

    public function add($path, $name)
    {
        if ( ! $this->archive) {
            throw new \Exception('잘못된 접근입니다.');
        }

        $this->archive->addFromString($name, file_get_contents($path));
    }

    public function queue($files, $path, $disk)
    {
        $jobs = collect([]);

        $temporary = new RemoteTemporaryFile($disk);

        collect($files)->chunk(500)->each(function ($chunks) use ($temporary, $jobs) {
            $jobs->push(new AppendFileJob($temporary, $chunks));
        });

        $jobs->push(new ReleaseZipJob($temporary, $path));

        return new PendingDispatch(
            (new CreateZipJob($temporary))->chain($jobs->toArray())
        );
    }

    public function close()
    {
        if ( ! $this->archive) {
            throw new \Exception('잘못된 접근입니다.');
        }

        $this->archive->close();
        $this->archive = null;

        return $this;
    }
}

