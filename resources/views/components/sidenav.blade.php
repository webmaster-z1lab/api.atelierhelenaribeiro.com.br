<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="https://d35c048n9fix3e.cloudfront.net/images/z1lab/logo/logo_full.svg" class="navbar-brand-img" alt="Main logo">
            </a>
            <div class="ml-auto">
                <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="navbar-inner">
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="ni ni-collection"></i>
                            <span class="nav-link-text">Catálogo</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">
                            <i class="ni ni-box-2 text-info"></i>
                            <span class="nav-link-text">Produtos</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="ni ni-folder-17 text-warning"></i>
                            <span class="nav-link-text">Clientes</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="ni ni-single-02 text-primary"></i>
                            <span class="nav-link-text">Funcionários</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="../../pages/calendar.html">
                            <i class="ni ni-calendar-grid-58 text-red"></i>
                            <span class="nav-link-text">Vendas</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
