<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 20/08/2019
 * Time: 09:50
 */
return [
    'driver'    => 'gd',
    'sizes'     => [
        'basic'     => [
            'w' => 1280,
            'h' => 720,
            'placeholder' => 'https://via.placeholder.com/1280x720'
        ],
        'square'    => [
            'w' => 640,
            'h' => 640,
            'placeholder' => 'https://via.placeholder.com/640'
        ],
        'thumbnail' => [
            'w' => 250,
            'h' => 250,
            'placeholder' => 'https://via.placeholder.com/250'
        ],

    ],
    'extension' => 'webp',
    'quality'   => 80,
    'path'      => 'images',
];
