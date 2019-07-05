@extends('layouts.default')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-xl-4 col-md-6">
            <div class="card bg-gradient-primary border-0">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0 text-white">Vestidos finalizados</h5>
                            <span class="h2 font-weight-bold mb-0 text-white">22/100</span>
                            <div class="progress progress-xs mt-3 mb-0">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100" style="width: 22%;"></div>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-sm">
                        <a href="#" class="text-nowrap text-white font-weight-600">Ver detalhes</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card bg-gradient-info border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0 text-white">Pedidos Entregues</h5>
                            <span class="h2 font-weight-bold mb-0 text-white">5/25</span>
                            <div class="progress progress-xs mt-3 mb-0">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="25" style="width: 20%;"></div>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-sm">
                        <a href="#" class="text-nowrap text-white font-weight-600">Ver detalhes</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card bg-gradient-danger border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0 text-white">Vestidos vendidos</h5>
                            <span class="h2 font-weight-bold mb-0 text-white">80/100</span>
                            <div class="progress progress-xs mt-3 mb-0">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;"></div>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-0 text-sm">
                        <a href="#" class="text-nowrap text-white font-weight-600">Ver detalhes</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="card-deck flex-column flex-xl-row">
        <div class="card">
            <div class="card-header bg-transparent">
                <h6 class="text-muted text-uppercase ls-1 mb-1">Resumo</h6>
                <h2 class="h3 mb-0">Desempenho das vendas</h2>
            </div>
            <div class="card-body">
                <!-- Chart -->
                <div class="chart">
                    <!-- Chart wrapper -->
                    <canvas id="chart-sales" class="chart-canvas"></canvas>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header bg-transparent">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="text-uppercase text-muted ls-1 mb-1">Desempenho</h6>
                        <h2 class="h3 mb-0">Total de vendas</h2>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Chart -->
                <div class="chart">
                    <canvas id="chart-bars" class="chart-canvas"></canvas>
                </div>
            </div>
        </div>
        <!-- Progress track -->
        <div class="card">
            <!-- Card header -->
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <!-- Surtitle -->
                        <h6 class="surtitle">5/23 vestidos</h6>
                        <!-- Title -->
                        <h5 class="h3 mb-0">Acompanhamento de progresso</h5>
                    </div>
                </div>
            </div>
            <!-- Card body -->
            <div class="card-body">
                <!-- List group -->
                <ul class="list-group list-group-flush list my--3">
                    <li class="list-group-item px-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <!-- Avatar -->
                                <a href="#" class="avatar rounded-circle">
                                    <img alt="Image placeholder" src="{{ asset('assets/img/theme/sketch.jpg') }}">
                                </a>
                            </div>
                            <div class="col">
                                <h5>Vestido noiva REF 123456</h5>
                                <div class="progress progress-xs mb-0">
                                    <div class="progress-bar bg-orange" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item px-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <!-- Avatar -->
                                <a href="#" class="avatar rounded-circle">
                                    <img alt="Image placeholder" src="{{ asset('assets/img/theme/sketch.jpg') }}">
                                </a>
                            </div>
                            <div class="col">
                                <h5>Vestido preto REF 45645645</h5>
                                <div class="progress progress-xs mb-0">
                                    <div class="progress-bar bg-green" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item px-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <!-- Avatar -->
                                <a href="#" class="avatar rounded-circle">
                                    <img alt="Image placeholder" src="{{ asset('assets/img/theme/sketch.jpg') }}">
                                </a>
                            </div>
                            <div class="col">
                                <h5>Vestido vermelho REF 159753</h5>
                                <div class="progress progress-xs mb-0">
                                    <div class="progress-bar bg-red" role="progressbar" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100" style="width: 72%;"></div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item px-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <!-- Avatar -->
                                <a href="#" class="avatar rounded-circle">
                                    <img alt="Image placeholder" src="{{ asset('assets/img/theme/sketch.jpg') }}">
                                </a>
                            </div>
                            <div class="col">
                                <h5>Vestido azul REF 788787</h5>
                                <div class="progress progress-xs mb-0">
                                    <div class="progress-bar bg-teal" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%;"></div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item px-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <!-- Avatar -->
                                <a href="#" class="avatar rounded-circle">
                                    <img alt="Image placeholder" src="{{ asset('assets/img/theme/sketch.jpg') }}">
                                </a>
                            </div>
                            <div class="col">
                                <h5>Vestido rosa REF 252525</h5>
                                <div class="progress progress-xs mb-0">
                                    <div class="progress-bar bg-green" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Encomendas</h3>
                        </div>
                        <div class="col text-right">
                            <a href="#" class="btn btn-sm btn-primary">Ver todas</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <!-- Projects table -->
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name">Vestido</th>
                                <th scope="col" class="sort" data-sort="budget">Valor</th>
                                <th scope="col" class="sort" data-sort="status">Situação</th>
                                <th scope="col" class="sort" data-sort="completion">Progresso</th>
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
                                            <span class="name mb-0 text-sm">Vestido azul REF 123123</span>
                                        </div>
                                    </div>
                                </th>
                                <td class="budget">
                                    R$ 1000
                                </td>
                                <td>
                                    <span class="badge badge-dot mr-4">
                                      <i class="bg-warning"></i>
                                      <span class="status">pendente</span>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="completion mr-2">60%</span>
                                        <div>
                                            <div class="progress">
                                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="#">Detalhes do produto</a>
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
                                            <span class="name mb-0 text-sm">Vestido noiva REF12121554</span>
                                        </div>
                                    </div>
                                </th>
                                <td class="budget">
                                    R$ 1800
                                </td>
                                <td>
                                    <span class="badge badge-dot mr-4">
                                      <i class="bg-success"></i>
                                      <span class="status">Finalizado</span>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="completion mr-2">100%</span>
                                        <div>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="#">Detalhes do produto</a>
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
                                            <span class="name mb-0 text-sm">Vestido preto REF 121211</span>
                                        </div>
                                    </div>
                                </th>
                                <td class="budget">
                                    R$900
                                </td>
                                <td>
                        <span class="badge badge-dot mr-4">
                          <i class="bg-danger"></i>
                          <span class="status">Atrasado</span>
                        </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="completion mr-2">72%</span>
                                        <div>
                                            <div class="progress">
                                                <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100" style="width: 72%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="#">Detalhes do produto</a>
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
                                            <span class="name mb-0 text-sm">Vestido bege REF000654</span>
                                        </div>
                                    </div>
                                </th>
                                <td class="budget">
                                    R$ 1500
                                </td>
                                <td>
                                    <span class="badge badge-dot mr-4">
                                      <i class="bg-info"></i>
                                      <span class="status">na fila</span>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="completion mr-2">90%</span>
                                        <div>
                                            <div class="progress">
                                                <div class="progress-bar bg-info" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="#">Detalhes do produto</a>
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
                                            <span class="name mb-0 text-sm">Vestido palha REF 114477</span>
                                        </div>
                                    </div>
                                </th>
                                <td class="budget">
                                    R$ 2200
                                </td>
                                <td>
                                    <span class="badge badge-dot mr-4">
                                      <i class="bg-success"></i>
                                      <span class="status">finalizado</span>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="completion mr-2">100%</span>
                                        <div>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="#">Detalhes do produto</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
