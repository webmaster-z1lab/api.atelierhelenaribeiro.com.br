<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 03/07/2019
 * Time: 19:56
 */

namespace App\Traits;

trait PropertyPath
{
    /**
     * @param  object  $object
     * @param  string  $property_path
     *
     * @return bool
     */
    public static function property_path_exists($object, string $property_path)
    {
        $path_components = explode('->', $property_path);

        if (count($path_components) === 1) return property_exists($object, $property_path);
        if (is_array($object)) return FALSE;

        return (property_exists($object, $path_components[0]) &&
                static::property_path_exists($object->{array_shift($path_components)}, implode('->', $path_components))
        );
    }

}
