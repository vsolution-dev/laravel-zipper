<?php

namespace VSolutionDev\LaravelZipper\Files;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocalTemporaryFile
{
    protected $path;

    public function __construct()
    {
        $this->path = $this->getTemporaryFile();
    }

    private function getTemporaryFile()
    {
        $filename = storage_path('zipper-' . Str::random(32) . '.zip');

        touch($filename);

        return realpath($filename);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function copyTo($path, $disk)
    {
        return Storage::disk($disk)->put($path, file_get_contents($this->getPath()));
    }

    public function copyFrom($path, $disk)
    {
        return file_put_contents($this->path, Storage::disk($disk)->get($path));
    }

    public function delete()
    {
        return unlink($this->path);
    }
}
