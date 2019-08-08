<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 08/08/2019
 * Time: 16:24
 */

namespace Tests;


use Faker\Generator as Faker;
use Faker\Provider\Base;
use Faker\Provider\pt_BR\Person;
use Intervention\Image\ImageManager;

trait Base64Files
{
    /**
     * @return array
     */
    public function getBase64Images(): array
    {
        $faker = new Faker();
        $faker->addProvider(new Base($faker));
        $faker->addProvider(new Person($faker));

        $images = [];
        $total = $faker->numberBetween(1, 5);

        for ($i = 0; $i < $total; $i++) {
            $image = (new ImageManager())->canvas(1200, 720)->encode('data-url');

            $images[] = [
                'dataURL' => $image->encoded,
                'upload'  => [
                    'filename' => $faker->name.'.webp',
                    'total'    => $faker->numberBetween(1, 20000),
                ],
            ];
        }

        return $images;
    }

    /**
     * @return array
     */
    public function getBase64Image(): array
    {
        $faker = new Faker();
        $faker->addProvider(new Base($faker));
        $faker->addProvider(new Person($faker));

        $image = (new ImageManager())->canvas(1200, 720)->encode('data-url');

        return [
            'dataURL' => $image->encoded,
            'upload'  => [
                'filename' => $this->faker->name.'.webp',
                'total'    => $faker->numberBetween(1, 20000),
            ],
        ];
    }
}
