@extends('layouts.default')

@section('title', 'Cadastrar produto')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="#" class="my-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="example2cols2Input">Referência</label>
                            <input type="text" class="form-control" id="example2cols2Input" placeholder="REF XXX">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label" for="example2cols2Input">Cor</label>
                            <input type="text" class="form-control" id="example2cols2Input" placeholder="Azul">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-control-label" for="exampleFormControlSelect1">Tamanho</label>
                        <select class="form-control" id="exampleFormControlSelect1">
                            <option>P</option>
                            <option>M</option>
                            <option>G</option>
                            <option>XG</option>
                            <option>XXG</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="example-date-input" class="form-control-label">Data de entrega</label>
                        <input class="form-control" type="date" value="2019-11-23" id="example-date-input">
                    </div>

                    <div class="col-12 mt-2">
                        <label for="customFileUploadMultiple">Fotos</label>

                        <div class="dropzone dropzone-multiple" data-toggle="dropzone" data-dropzone-multiple data-dropzone-url="http://">
                            <div class="fallback">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFileUploadMultiple" multiple>
                                    <label class="custom-file-label" for="customFileUploadMultiple">Escolha um arquivo</label>
                                </div>
                            </div>
                            <ul class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush">
                                <li class="list-group-item px-0">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="avatar">
                                                <img class="avatar-img rounded" src="..." alt="..." data-dz-thumbnail>
                                            </div>
                                        </div>
                                        <div class="col ml--3">
                                            <h4 class="mb-1" data-dz-name>...</h4>
                                            <p class="small text-muted mb-0" data-dz-size>...</p>
                                        </div>
                                        <div class="col-auto">
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-ellipses dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fe fe-more-vertical"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="#" class="dropdown-item" data-dz-remove>
                                                        Remover
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-control-label" for="exampleFormControlTextarea2">Observações</label>
                            <textarea class="form-control" id="exampleFormControlTextarea2" rows="3" resize="none"></textarea>
                        </div>
                    </div>

                    <div class="col-6">
                        <button type="button" class="btn btn-secondary btn-block">Voltar</button>
                    </div>

                    <div class="col-6">
                        <button type="button" class="btn btn-primary btn-block">Cadastrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
