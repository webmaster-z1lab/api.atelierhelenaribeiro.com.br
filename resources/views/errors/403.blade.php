@extends('errors.layout')

@section('title', '403 - Não permitido')
@section('body-class', 'bg-img-hero-fixed')
@section('body-style', 'background-image: url(https://d35c048n9fix3e.cloudfront.net/images/undraw/undraw_security_o890.svg);')

@section('content')
    <div class="d-lg-flex">
        <div class="container d-lg-flex align-items-lg-center min-height-lg-100vh space-4">
            <div class="w-lg-60 w-xl-50">
                <div class="mb-5 bg-primary p-5">
                    <h1 class="font-weight-normal text-dark">403 - Acesso <span class="font-weight-semi-bold">negado</span></h1>
                    <p class="mb-0 text-dark">Oops! Você não possui as credenciais necessárias para acessar esse recurso.</p>
                    <p class="text-dark">Se você acredita que isso é um erro, por favor <a class="text-dark" href="https://tawk.to/chat/5b789d4dafc2c34e96e7b2ec/default"
                                                                                           target="_blank">nos avise</a>.</p>
                </div>

                <a class="btn btn-primary btn-wide transition-3d-hover" href="/"><i class="fas fa-home"></i> Ir para Home</a>
            </div>
        </div>
    </div>
@endsection
