@extends('errors.layout')

@section('title', 'Voltamos já')

@section('body-class', 'bg-light')

@section('content')
    <div class="d-lg-flex">
        <div class="container d-lg-flex align-items-lg-center min-height-lg-100vh space-top-4 space-bottom-2 space-lg-0">
            <div class="w-100 mx-auto">
                <figure class="mb-5 ie-maintenance-mode">
                    <img src="https://d35c048n9fix3e.cloudfront.net/template/front/2.0.1/svg/illustrations/maintenance-mode.svg" alt="Image Description">
                </figure>

                <div class="w-md-80 w-lg-50 text-center mx-md-auto">
                    <div class="mb-5">
                        <h1 class="h2 font-weight-normal">Nós estamos realizando alguns ajustes</h1>

                        <p>
                            Pedimos desculpas pela inconveniência mas o <strong>quantofica.com</strong> está passando por uma manuntenção programada.
                        </p>

                        <p class="lead text-uppercase">
                            <strong>Voltaremos em alguns minutos.</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
