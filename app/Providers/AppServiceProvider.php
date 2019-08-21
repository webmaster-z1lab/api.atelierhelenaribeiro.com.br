<?php

namespace App\Providers;

use App\Models\Image;
use App\Models\Price;
use App\Observers\ImageObserver;
use App\Observers\PriceObserver;
use App\Validator\Validator;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Resource::withoutWrapping();

        $me = $this;

        $this->app['validator']->resolver(function ($translator, $data, $rules, $messages, $customAttributes) use ($me) {
            $messages += $me->getMessages();

            return new Validator($translator, $data, $rules, $messages, $customAttributes);
        });

        Price::observe(PriceObserver::class);
        //Image::observe(ImageObserver::class);
    }

    /**
     * @return array
     */
    protected function getMessages()
    {
        return [
            'cell_phone'  => 'O campo :attribute não é um possui o formato válido de celular com DDD',
            'cnpj'        => 'O campo :attribute não é um CNPJ válido',
            'cpf'         => 'O campo :attribute não é um CPF válido',
            'bool_custom' => 'O campo :attribute deve ser verdadeiro ou falso',
        ];
    }
}
