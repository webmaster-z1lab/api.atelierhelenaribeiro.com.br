@extends('layouts.auth')

@section('title', 'Recuperar acesso')
@section('description', 'Utilize o formulário abaixo para enviar uma requisição de nova senha para o seu e-mail cadastrado no sistema.')

@section('content')
    <div class="col-lg-5 col-md-7">
        <div class="card bg-secondary border-0 mb-0">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="card-body px-lg-5 py-lg-5">
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <div class="input-group input-group-merge input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                            </div>

                            <label class="sr-only" for="email">E-mail</label>
                            <input class="form-control" placeholder="Email" type="email" id="email" name="email" required>
                        </div>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-primary my-4"><i class="far fa-paper-plane mr-1"></i>Enviar</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-6">
                <a href="{{ route('login') }}" class="text-light"><small><i class="fas fa-arrow-left mr-1"></i>Voltar para o Login</small></a>
            </div>
        </div>
    </div>
@endsection
