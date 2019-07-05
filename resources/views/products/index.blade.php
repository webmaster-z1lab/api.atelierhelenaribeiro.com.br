@extends('layouts.default')

@section('title', 'Produtos')

@section('actions')
    <a href="{{ route('products.save') }}" class="btn btn-primary btn-sm">Novo produto</a>
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" data-sort="name">Produto</th>
                            <th scope="col" class="sort" data-sort="budget">valor</th>
                            <th scope="col" class="sort" data-sort="status">Situação</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        <tr>
                            <th scope="row">
                                <div class="media align-items-center">
                                    <a href="#" class="avatar rounded-circle mr-3">
                                        <img alt="Image placeholder" src="{{ asset('assets/img/theme/sketch.jpg') }}">
                                    </a>
                                    <div class="media-body">
                                        <span class="name mb-0 text-sm">Vestido 112233</span>
                                    </div>
                                </div>
                            </th>
                            <td class="budget">
                                R$ 1000
                            </td>
                            <td>
                              <span class="badge badge-dot mr-4">
                                <i class="bg-warning"></i>
                                <span class="status">consignado</span>
                              </span>
                            </td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="#">Editar</a>
                                        <a class="dropdown-item" href="#">Excluir</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <div class="media align-items-center">
                                    <a href="#" class="avatar rounded-circle mr-3">
                                        <img alt="Image placeholder" src="{{ asset('assets/img/theme/sketch.jpg') }}">
                                    </a>
                                    <div class="media-body">
                                        <span class="name mb-0 text-sm">Vestido 112233</span>
                                    </div>
                                </div>
                            </th>
                            <td class="budget">
                                R$ 1000
                            </td>
                            <td>
                              <span class="badge badge-dot mr-4">
                                <i class="bg-success"></i>
                                <span class="status">disponível</span>
                              </span>
                            </td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="#">Editar</a>
                                        <a class="dropdown-item" href="#">Excluir</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <div class="media align-items-center">
                                    <a href="#" class="avatar rounded-circle mr-3">
                                        <img alt="Image placeholder" src="{{ asset('assets/img/theme/sketch.jpg') }}">
                                    </a>
                                    <div class="media-body">
                                        <span class="name mb-0 text-sm">Vestido 112233</span>
                                    </div>
                                </div>
                            </th>
                            <td class="budget">
                                R$ 1000
                            </td>
                            <td>
                              <span class="badge badge-dot mr-4">
                                <i class="bg-primary"></i>
                                <span class="status">vendido</span>
                              </span>
                            </td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="#">Editar</a>
                                        <a class="dropdown-item" href="#">Excluir</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <div class="media align-items-center">
                                    <a href="#" class="avatar rounded-circle mr-3">
                                        <img alt="Image placeholder" src="{{ asset('assets/img/theme/sketch.jpg') }}">
                                    </a>
                                    <div class="media-body">
                                        <span class="name mb-0 text-sm">Vestido 112233</span>
                                    </div>
                                </div>
                            </th>
                            <td class="budget">
                                R$ 1000
                            </td>
                            <td>
                              <span class="badge badge-dot mr-4">
                                <i class="bg-warning"></i>
                                <span class="status">consignado</span>
                              </span>
                            </td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="#">Editar</a>
                                        <a class="dropdown-item" href="#">Excluir</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <div class="media align-items-center">
                                    <a href="#" class="avatar rounded-circle mr-3">
                                        <img alt="Image placeholder" src="{{ asset('assets/img/theme/sketch.jpg') }}">
                                    </a>
                                    <div class="media-body">
                                        <span class="name mb-0 text-sm">Vestido 112233</span>
                                    </div>
                                </div>
                            </th>
                            <td class="budget">
                                R$ 1000
                            </td>
                            <td>
                              <span class="badge badge-dot mr-4">
                                <i class="bg-warning"></i>
                                <span class="status">consignado</span>
                              </span>
                            </td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="#">Editar</a>
                                        <a class="dropdown-item" href="#">Excluir</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <div class="media align-items-center">
                                    <a href="#" class="avatar rounded-circle mr-3">
                                        <img alt="Image placeholder" src="{{ asset('assets/img/theme/sketch.jpg') }}">
                                    </a>
                                    <div class="media-body">
                                        <span class="name mb-0 text-sm">Vestido 112233</span>
                                    </div>
                                </div>
                            </th>
                            <td class="budget">
                                R$ 1000
                            </td>
                            <td>
                              <span class="badge badge-dot mr-4">
                                <i class="bg-warning"></i>
                                <span class="status">consignado</span>
                              </span>
                            </td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="#">Editar</a>
                                        <a class="dropdown-item" href="#">Excluir</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Card footer -->
                <div class="card-footer py-4">
                    <nav aria-label="...">
                        <ul class="pagination justify-content-end mb-0">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">
                                    <i class="fas fa-angle-left"></i>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">
                                    <i class="fas fa-angle-right"></i>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
