<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 26/06/2019
 * Time: 11:46
 */

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

trait FileUpload
{
    /**
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param                                 $path
     *
     * @return array
     */
    public function upload(UploadedFile $file, $path): array
    {
        $aux = [];

        $aux['extension'] = $file->extension();
        $aux['size_in_bytes'] = $file->getSize();
        $aux['name'] = $this->getName($file, $path);

        $fileName = Str::slug($aux['name']).".".$aux['extension'];
        $aux['path'] = \Storage::putFileAs($path, $file, $fileName);

        return $aux;
    }

    /**
     * @param  array   $files
     * @param  string  $path
     *
     * @return array
     */
    public function uploadMany(array $files, string $path): array
    {
        $uploaded = [];

        foreach ($files as $file) {
            $uploaded[] = $this->upload($file, $path);
        }

        return $uploaded;
    }

    /**
     * @param  array   $request
     * @param  string  $path
     * @param  string  $key
     *
     * @return array|bool|string
     */
    public function uploadFromRequest(array $request, string $path, string $key)
    {
        if (array_key_exists($key, $request) && filled($request[$key])) {
            if ($request[$key] instanceof UploadedFile) return $this->upload($request[$key], $path);

            return $this->uploadMany($request[$key], $path);
        }

        return FALSE;
    }


    /**
     * @param  array   $file
     * @param  string  $path
     * @param  string  $extension
     *
     * @return array
     */
    public function uploadBase64(array $file, string $path, string $extension = 'webp')
    {
        $aux = [];

        $data = (new ImageManager())->make($file['dataURL']);

        $aux['extension'] = $extension;
        $aux['size_in_bytes'] = $file['upload']['total'];
        $aux['name'] = explode('.', $file['upload']['filename'])[0];
        $aux['path'] = $path.'/'.$file['upload']['filename'];

        \Storage::put($aux['path'], $data->encode($extension, 80)->getEncoded());

        return $aux;
    }

    /**
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string                         $path
     *
     * @return string
     */
    private function getName(UploadedFile $file, string $path): string
    {
        $tempName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $tempName = preg_replace("/[^a-zA-Z0-9\s]/", "", $tempName);

        $i = 0;

        do {
            ($i === 0) ? $name = $tempName : $name = "$tempName $i";

            $binary = $path."/".Str::slug($name).".".$file->extension();
            $i++;
        } while (\Storage::exists($binary));

        return $name;
    }
}
