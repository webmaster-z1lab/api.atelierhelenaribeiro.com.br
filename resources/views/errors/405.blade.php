@extends('errors.layout')

@section('title', '405 - Método não permitido')
@section('body-class', 'bg-img-hero-fixed')
@section('body-style', 'background-image: url(https://d35c048n9fix3e.cloudfront.net/images/undraw/undraw_be_the_hero_ssr2.svg);')

@section('content')
    <div class="d-lg-flex">
        <div class="container d-lg-flex align-items-lg-center min-height-lg-100vh space-4">
            <div class="w-lg-70 w-xl-60">
                <div class="mb-5">
                    <h1 class="text-primary font-weight-normal">405 - Método não <span class="font-weight-semi-bold">permitido</span></h1>
                    <p class="mb-0">Oops! Essa recurso não pode ser acessado utilizando o método atual.</p>
                </div>

                <a class="btn btn-primary btn-wide transition-3d-hover" href="/"><i class="fas fa-home"></i> Ir pra Home</a>
            </div>
        </div>
    </div>
@endsection
