<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Image;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

$factory->define(Image::class, function (Faker $faker) {
    Storage::persistentFake('local');

    $name = Str::uuid()->toString().'.png';

    $file = UploadedFile::fake()->image($name, 2000, 1500);
    $path = $file->storeAs('tests', $name);

    return [
        'name'          => $name,
        'path'          => $path,
        'template_id'   => NULL,
        'extension'     => $file->getClientOriginalExtension(),
        'mime_type'     => $file->getMimeType(),
        'size_in_bytes' => $file->getSize(),
    ];
});
