<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 16/12/2018
 * Time: 13:10
 */

namespace App\Services;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic;
use \Intervention\Image\Image;

class ImageProcessor
{
    /**
     * @var int
     */
    private $quality;
    /**
     * @var string
     */
    private $extension;
    /**
     * @var string
     */
    private $path;
    /**
     * @var string
     */
    private $source;
    /**
     * @var string
     */
    private $file;
    /**
     * @var array
     */
    private $types;

    /**
     * ImageProcessor constructor.
     */
    public function __construct()
    {
        $this->extension = config('image.extension');
        $this->quality = config('image.quality');
        $this->types = array_keys(config('image.sizes'));
    }

    /**
     * Process and generate all image formats needed
     *
     * @return array
     */
    public function process(): array
    {
        $images = [];

        foreach ($this->types as $type) {
            $method = Str::camel($type);

            if (method_exists($this, $method)) $images[$type] = $this->$method();
        }

        return $images;
    }

    /**
     * @param  string  $path
     *
     * @return $this
     */
    public function setPath(string $path)
    {
        $this->path = config('image.path')."/$path/";

        return $this;
    }

    /**
     * @param  string  $source
     *
     * @return $this
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function setSource(string $source)
    {
        $this->source = $source;
        $this->file = Storage::get($source);

        return $this;
    }

    /**
     * @return string
     */
    public function cover(): string
    {
        return $this->create('cover');
    }

    /**
     * @return string
     */
    public function square(): string
    {
        return $this->create('square');
    }

    /**
     * @return string
     */
    public function thumbnail(): string
    {
        return $this->create('thumbnail');
    }

    /**
     * @return string
     */
    public function basic(): string
    {
        return $this->create('basic', 'webp', FALSE);
    }

    /**
     * Remove source Image
     */
    public function destroyOriginal(): void
    {
        Storage::delete($this->source);
    }

    /**
     * @param  string       $type
     * @param  string|NULL  $extension
     * @param  bool         $crop
     *
     * @return string
     */
    private function create(string $type, string $extension = NULL, bool $crop = TRUE): string
    {
        $extension = $extension ?? $this->extension;
        $fileName = (string) Str::uuid()."__$type.$extension";
        $path = $this->path.$fileName;

        $image = ImageManagerStatic::make($this->file);

        if ($crop) {
            $this->resizeAndCrop($image, $type);
        } else {
            $this->resize($image, $type);
        }

        Storage::put($path, $image->encode($extension, $this->quality)->getEncoded());

        $image->destroy();

        return $path;
    }

    /**
     * @param \Intervention\Image\Image $image
     * @param $type
     */
    private function resizeAndCrop(Image &$image, string $type): void
    {
        $size = config("image.sizes.$type");

        $image->resize(NULL, $size[0], function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->crop($size[0], $size[1]);
    }

    /**
     * @param  \Intervention\Image\Image  $image
     * @param  string                     $type
     */
    private function resize(Image &$image, string $type)
    {
        $size = config("image.sizes.$type");

        if ($image->filesize() > $size[0]) {
            $image->resize($size[0], NULL, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
    }
}
