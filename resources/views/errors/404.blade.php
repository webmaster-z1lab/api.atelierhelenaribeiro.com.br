@extends('errors.layout')

@section('title', '404 - Página não encontrada')
@section('body-class', 'bg-img-hero-fixed')
@section('body-style', 'background-image: url(https://d35c048n9fix3e.cloudfront.net/template/front/2.0.1/svg/illustrations/error-404.svg);')

@section('content')
    <div class="d-lg-flex">
        <div class="container d-lg-flex align-items-lg-center min-height-lg-100vh space-4">
            <div class="w-lg-70 w-xl-60">
                <div class="mb-5">
                    <h1 class="text-primary font-weight-normal">404 - Página não <span class="font-weight-semi-bold">encontrada</span></h1>
                    <p class="mb-0">Oops! parece que você digitou um link errado.</p>
                    <p>Se você acredita que isso é um erro, por favor <a href="https://tawk.to/chat/5b789d4dafc2c34e96e7b2ec/default" target="_blank">nos avise</a>.</p>
                </div>

                <a class="btn btn-primary btn-wide transition-3d-hover" href="/"><i class="fas fa-home"></i> Ir pra Home</a>
            </div>
        </div>
    </div>
@endsection
