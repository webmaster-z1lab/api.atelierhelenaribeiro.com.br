@extends('layouts.auth')

@section('title', 'Cadastar nova senha')
@section('description', 'Utilize o formulário abaixo para cadastrar uma nova senha de acesso')

@section('content')
    <div class="col-lg-5 col-md-7">
        <div class="card bg-secondary border-0 mb-0">
            <div class="card-body px-lg-5 py-lg-5">
                <form method="POST" action="{{ route('password.update') }}">
                    <input type="hidden" name="token" value="{{ $token }}">
                    @csrf

                    @if($errors->any())
                        <div class="text-center my-4 text-danger">
                            <strong>{{ $errors->first('email') }}</strong>
                        </div>
                    @endif

                    <div class="form-group mb-3">
                        <div class="input-group input-group-merge input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                            </div>
                            <label class="sr-only" for="email">E-mail</label>
                            <input class="form-control" placeholder="Email" type="email" id="email" name="email" required>
                        </div>

                        @if ($errors->has('email'))
                            <div class="invalid-feedback" style="display: block;">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group  mb-3">
                        <div class="input-group input-group-merge input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                            </div>
                            <label class="sr-only" for="password">Senha</label>
                            <input class="form-control" placeholder="Senha" type="password" name="password" id="password" required>
                        </div>

                        @if ($errors->has('password'))
                            <div class="invalid-feedback" style="display: block;">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="input-group input-group-merge input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                            </div>

                            <label class="sr-only" for="password_confirmation">Confirmação da senha</label>
                            <input class="form-control" placeholder="Confirmação da senha" type="password" name="password_confirmation" id="password_confirmation" required>
                        </div>

                        @if ($errors->has('password_confirmation'))
                            <div class="invalid-feedback" style="display: block;">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>

                    <div class="text-center">
                        <button class="btn btn-primary my-4"><i class="fas fa-sign-in-alt mr-1"></i>Enviar</button>
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
