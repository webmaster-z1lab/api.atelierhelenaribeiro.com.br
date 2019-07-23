@extends('errors.layout')

@section('title', '500 - Falha do servidor')
@section('body-class', 'bg-img-hero-fixed')
@section('body-style', 'background-image: url(https://d35c048n9fix3e.cloudfront.net/images/undraw/undraw_may_the_force_bgdm.svg);')

@section('content')
    <div class="d-lg-flex">
        <div class="container d-lg-flex align-items-lg-center min-height-lg-100vh space-4">
            <div class="w-lg-60 w-xl-50 bg-white p-5">
                <div class="mb-5">
                    <h1 class="font-weight-normal text-danger">500 - Falha no <span class="font-weight-semi-bold">servidor</span></h1>
                    <p class="mb-0 text-danger">Uma perturbação na força causou uma falha no nosso sistema.</p>
                    <p class="mb-0 text-danger">Se você acredita que isso é um bug, por favor <a class="text-danger" href="https://tawk.to/chat/5b789d4dafc2c34e96e7b2ec/default"
                                                                                                 target="_blank">nos avise</a>.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
