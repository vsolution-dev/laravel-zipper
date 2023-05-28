<?php

namespace VSolutionDev\LaravelZipper;

use Illuminate\Foundation\Bus\PendingDispatch;
use VSolutionDev\LaravelZipper\Files\RemoteTemporaryFile;
use VSolutionDev\LaravelZipper\Jobs\AppendFileJob;
use VSolutionDev\LaravelZipper\Jobs\CreateZipJob;
use VSolutionDev\LaravelZipper\Jobs\ReleaseZipJob;

class Zipper
{

    /**
     * @var \ZipArchive
     */
    protected $archive;

    /**
     * @param $name
     * @param $flags
     * @return $this
     * @throws \Exception
     */
    public function create($name, $flags = \ZipArchive::CREATE | \ZipArchive::OVERWRITE)
    {
        return tap($this->open($name, $flags), function () {
            $this->archive->addEmptyDir('.');
        });
    }

    private function throwIfInvalidArchive()
    {
        if ( ! $this->archive) {
            throw new \Exception('`archive` is not defined. Please call `create` or `open` function first.');
        }
    }

    /**
     * @param $path
     * @param $flags
     * @return $this
     * @throws \Exception
     */
    public function open($path, $flags = null)
    {
        if ($this->archive) {
            $this->close();
        }

        $archive = new \ZipArchive;
        if ($archive->open($path, $flags) !== TRUE) {
            throw new \Exception('Unable to read the archive file.');
        }

        $this->archive = $archive;

        return $this;
    }

    /**
     * @param string $path
     * @return $this
     * @throws \Exception
     */
    public function extract($path)
    {
        $this->throwIfInvalidArchive();

        $this->archive->extractTo($path);

        return $this;
    }

    /**
     * @param string $path
     * @param string $name
     * @return $this
     * @throws \Exception
     */
    public function add($path, $name)
    {
        $this->throwIfInvalidArchive();

        $this->archive->addFromString($name, file_get_contents($path));

        return $this;
    }

    /**
     * @param $files
     * @param $path
     * @param $disk
     * @param $chunk
     * @return PendingDispatch
     */
    public function queue($files, $path, $disk, $chunk = 500)
    {
        $jobs = collect([]);

        $temporary = new RemoteTemporaryFile($disk);

        collect($files)->chunk($chunk)->each(function ($chunks) use ($temporary, $jobs) {
            $jobs->push(new AppendFileJob($temporary, $chunks));
        });

        $jobs->push(new ReleaseZipJob($temporary, $path));

        return new PendingDispatch(
            (new CreateZipJob($temporary))->chain($jobs->toArray())
        );
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function close()
    {
        $this->throwIfInvalidArchive();

        $this->archive->close();
        $this->archive = null;

        return $this;
    }
}

