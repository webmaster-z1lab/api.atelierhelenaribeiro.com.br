@extends('errors.layout')

@section('title', '401 - Não autorizado')
@section('body-class', 'bg-img-hero-fixed')
@section('body-style', 'background-image: url(https://d35c048n9fix3e.cloudfront.net/images/undraw/undraw_security_o890.svg);')

@section('content')
    <div class="d-lg-flex">
        <div class="container d-lg-flex align-items-lg-center min-height-lg-100vh space-4">
            <div class="w-lg-60 w-xl-60">
                <div class="mb-5 bg-primary p-5">
                    <h1 class="font-weight-normal text-dark">401 - Acesso não <span class="font-weight-semi-bold">permitido</span></h1>
                    <p class="mb-0 text-dark">Oops! Você precisa estar autenticado para acessar esse recurso.</p>
                    <p class="text-dark">Se você acredita que isso é um erro, por favor <a class="text-dark" href="https://tawk.to/chat/5b789d4dafc2c34e96e7b2ec/default"
                                                                                           target="_blank">nos avise</a>.</p>
                </div>

                @if(Route::has('login'))
                    <a class="btn btn-primary btn-wide transition-3d-hover" href="/login"><i class="fas fa-sign-in-alt"></i> Fazer login</a>
                @endif
            </div>
        </div>
    </div>
@endsection
