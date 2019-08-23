<?php

namespace Tests;

use Faker\Generator as Faker;
use Faker\Provider\Base;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ImageFiles
{
    /**
     * @var string
     */
    protected $fileDir = 'tests';
    /**
     * @var string
     */
    protected $disk = 'local';

    /**
     * @return array
     */
    public function getImages(): array
    {
        $faker = new Faker();
        $faker->addProvider(new Base($faker));

        $images = [];
        $total = $faker->numberBetween(1, 3);

        for ($i = 0; $i < $total; $i++) {
            $images[] = $this->getImage();
        }

        return $images;
    }

    /**
     * @return array
     */
    public function getImage(): array
    {
        Storage::persistentFake($this->disk);

        $name = Str::uuid()->toString().'.png';

        $file = UploadedFile::fake()->image($name, 2000, 1500);
        $path = $file->storeAs($this->fileDir, $name);

        return [
            'name'          => $name,
            'path'          => $path,
            'extension'     => $file->getClientOriginalExtension(),
            'mime_type'     => $file->getMimeType(),
            'size_in_bytes' => $file->getSize(),
        ];
    }

    /**
     * Clear the test directory
     */
    public function destroyImages(): void
    {
        Storage::disk($this->disk)->deleteDirectory($this->fileDir);
        Storage::disk($this->disk)->deleteDirectory('images');
    }
}
