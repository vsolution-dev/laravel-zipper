<?php

namespace VSolutionDev\LaravelZipper\Files;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RemoteTemporaryFile
{
    protected $path;
    protected $disk;
    protected $local;

    public function __construct($disk, $path = '/temporary')
    {
        $this->path = $this->getTemporaryFile($path);
        $this->disk = $disk;
        $this->local = new LocalTemporaryFile;
    }

    private function getTemporaryFile($path)
    {
        return rtrim($path, '/') . '/zipper-' . Str::random(32) . '.zip';
    }

    public function getLocalPath()
    {
        return $this->local->getPath();
    }

    public function getPath()
    {
        return $this->path;
    }

    public function moveTo($path)
    {
        return Storage::disk($this->disk)->move($this->getPath(), $path);
    }

    public function download()
    {
        return $this->local->copyFrom($this->path, $this->disk);
    }

    public function uploadAndDelete()
    {
        return $this->local->copyTo($this->getPath(), $this->disk)
            && $this->local->delete();
    }
}
