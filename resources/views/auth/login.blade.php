@extends('layouts.auth')

@section('title', 'Login')
@section('description', 'Utilize o formulário abaixo para acessar o sistema.')

@section('content')
    <div class="col-lg-5 col-md-7">
        <div class="card bg-secondary border-0 mb-0">
            <div class="card-header bg-transparent pb-5">
                <div class="text-muted text-center mt-2 mb-3"><small>Acesse com</small></div>
                <div class="btn-wrapper text-center">
                    <a href="#" class="btn btn-neutral btn-icon">
                        <span class="btn-inner--icon"><img src="{{ asset('assets/img/icons/common/facebook.png') }}" alt="Facebook icon"></span>
                        <span class="btn-inner--text">Facebook</span>
                    </a>

                    <a href="#" class="btn btn-neutral btn-icon">
                        <span class="btn-inner--icon"><img src="{{ asset('assets/img/icons/common/google.svg') }}" alt="Google icon"></span>
                        <span class="btn-inner--text">Google</span>
                    </a>
                </div>
            </div>
            <div class="card-body px-lg-5 py-lg-5">
                <div class="text-center text-muted mb-4">
                    <small>Ou utilize suas credenciais</small>
                </div>
                <form method="POST" action="{{ route('login') }}">
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
                    </div>
                    <div class="form-group">
                        <div class="input-group input-group-merge input-group-alternative">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                            </div>
                            <label class="sr-only" for="password">Senha</label>
                            <input class="form-control" placeholder="Senha" type="password" name="password" id="password" required>
                        </div>
                    </div>

                    <div class="custom-control custom-control-alternative custom-checkbox">
                        <input class="custom-control-input" id="remember" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="remember">
                            <span class="text-muted">Salvar meus dados</span>
                        </label>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-primary my-4"><i class="fas fa-sign-in-alt mr-1"></i>Acessar</button>
                    </div>
                </form>
            </div>
        </div>

        @if (Route::has('password.request'))
            <div class="row mt-3">
                <div class="col-6">
                    <a href="{{ route('password.request') }}" class="text-light"><small>Esqueceu sua senha?</small></a>
                </div>
            </div>
        @endif
    </div>
@endsection
