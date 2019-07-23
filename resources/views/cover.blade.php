<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">

    <title>CORE Api tikket.com.br | Um projeto Z1lab</title>

    <meta name="robots" content="no-index, no-follow">
    <meta name="author" content="z1lab.com.br">
    <meta name="locale" content="pt-BR">
    <meta name="theme-color" content="#6FBA00">
    <meta name="site_name" content="tikket.com">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="https://d35c048n9fix3e.cloudfront.net/images/tikket/icon.png">
    <link href="//fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link rel="stylesheet" href="https://d35c048n9fix3e.cloudfront.net/template/front/2.0.1/css/theme.min.css">
</head>
<body class="gradient-half-primary-body-v1 mb-lg-0">

<header id="header" class="u-header u-header--bg-transparent u-header--abs-top">
    <div class="u-header__section">
        <div id="logoAndNav" class="container">
            <nav class="navbar navbar-expand u-header__navbar">
                <div class="ml-auto">
                    <a class="text-white" href="//z1lab.com.br" target="_blank">
                        Um projeto <img src="https://d35c048n9fix3e.cloudfront.net/images/z1lab/logo/logo_white.svg" alt="Logo Z1lab" width="75px">
                    </a>
                </div>
            </nav>
        </div>
    </div>
</header>

<main id="content" role="main">
    <!-- Hero Section -->
    <div class="d-lg-flex" style="background: url(https://d35c048n9fix3e.cloudfront.net/template/front/2.0.1/img/bg-shapes/bg4.png) no-repeat right;">
        <div class="container d-lg-flex align-items-lg-center min-height-lg-100vh space-bottom-2 space-top-4 space-bottom-lg-3 space-lg-0">
            <div class="w-lg-80 w-xl-70 mt-lg-9">
                <div class="mb-6">
                    <img src="https://d35c048n9fix3e.cloudfront.net/images/tikket/logo-white.svg" alt="Logo Tikket" width="250px" class="mb-3">
                    <h1 class="text-white font-weight-normal">CORE Api - <small>Um projeto Z1lab</small></h1>
                    <p class="text-white-70">Essa é uma extensão do site <a class="text-white font-weight-bold" href="//tikket.com.br">tikket.com.br</a> que controla uma série de
                        funções que garantem o funcionamento da ferramenta.</p>
                    <p class="text-white-70">Assim como todo o projeto essa é uma solução desenvolvida e matida pela <a class="text-white font-weight-bold" href="//z1lab.com.br">Z1lab</a>.
                    </p>

                    <a class="btn btn-white btn-wide text-primary my-4 transition-3d-hover" href="//tikket.com.br"><i class="fas fa-globe-americas"></i> Ir para o Site</a>

                    <div class="mb-6">
                        <h6 class="text-white">SERVICES STATUS <a href="#" onclick="checkAgain()"><i id="refresh" class="fas fa-sync-alt fa-spin"></i></a></h6>

                        <hr>

                        <ul class="list-inline text-white">
                            <li class="list-inline-item" id="auth"><i class="fas fa-spinner fa-spin text-warning ml-1 status-check"></i> AUTH</li>
                            <li class="list-inline-item" id="core"><i class="fas fa-spinner fa-spin text-warning ml-1 status-check"></i> CORE</li>
                            <li class="list-inline-item" id="payment"><i class="fas fa-spinner fa-spin text-warning ml-1 status-check"></i> PAYMENT</li>
                            <li class="list-inline-item" id="admin"><i class="fas fa-spinner fa-spin text-warning ml-1 status-check"></i> ADMIN</li>
                            <li class="list-inline-item" id="portal"><i class="fas fa-spinner fa-spin text-warning ml-1 status-check"></i> PORTAL</li>
                        </ul>
                    </div>

                    <ul class="list-inline text-white mt-5">
                        <li class="list-inline-item" title="Laravel Framework"><i class="fab fa-2x fa-laravel"></i></li>
                        <li class="list-inline-item" title="VueJS"><i class="fab fa-2x fa-vuejs"></i></li>
                        <li class="list-inline-item" title="MongoDB"><i class="fab fa-2x fa-envira"></i></li>
                        <li class="list-inline-item" title="Amazon Web Services"><i class="fab fa-2x fa-aws"></i></li>
                        <li class="list-inline-item" title="Openid"><i class="fab fa-2x fa-openid"></i></li>
                        <li class="list-inline-item" title="Discord"><i class="fab fa-2x fa-discord"></i></li>
                        <li class="list-inline-item" title="Github"><i class="fab fa-2x fa-github"></i></li>
                        <li class="list-inline-item" title="The Force"><i class="fas fa-2x fa-jedi"></i></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->
</main>

<footer class="position-md-absolute right-md-0 bottom-md-0 left-md-0">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center space-1">

            <p class="small text-white mb-0">&copy; tikket.com.br | Z1Lab {{ date('Y') }}.</p>

            <ul class="list-inline mb-0 text-sm-right">
                <li class="list-inline-item">
                    <a class="btn btn-sm btn-icon btn-soft-light btn-bg-transparent" href="//facebook.com/tikketeventos">
                        <span class="fab fa-facebook-f btn-icon__inner"></span>
                    </a>
                </li>

                <li class="list-inline-item">
                    <a class="btn btn-sm btn-icon btn-soft-light btn-bg-transparent" href="//instagram.com/tikketeventos">
                        <span class="fab fa-instagram btn-icon__inner"></span>
                    </a>
                </li>

                <li class="list-inline-item">
                    <a class="btn btn-sm btn-icon btn-soft-light btn-bg-transparent" href="//z1lab.com.br">
                        <span class="fas fa-jedi btn-icon__inner" style="color: #6FBA00"></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</footer>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>
    let servers = [
        {
            "id": "payment",
            "url": "https://payment.tikket.com.br",
        },
        {
            "id": "auth",
            "url": "https://oauth.tikket.com.br",
        },
        {
            "id": "portal",
            "url": "https://tikket.com.br",
        },
        {
            "id": "admin",
            "url": "https://admin.tikket.com.br",
        },
        {
            "id": "core",
            "url": "https://api.tikket.com.br",
        }
    ];

    let loadingClass = "fas fa-spinner fa-spin text-warning ml-1 status-check";
    let successClass = "fas fa-check-double text-success ml-1 status-check";
    let failedClass = "fas fa-times text-danger ml-1 status-check";

    function checkAgain() {
        document.getElementById('refresh').classList.add('fa-spin');
        let list = document.querySelectorAll('.status-check');

        for (let j of list) {
            j.classList = loadingClass;
        }

        check();
    }

    function check() {
        setTimeout(function () {
            for (let i of servers) {
                testServer(i).then(function () {
                    document.getElementById('refresh').classList.remove('fa-spin');
                });
            }
        }, 1500);

    }

    async function testServer(server) {
        let target = document.getElementById(server.id).firstChild;

        try {
            const response = await axios.head(server.url);

            target.classList = (response.status === 200) ? successClass : failedClass;
        } catch (error) {
            target.classList = failedClass;
        }
    }

    (function () {
        check()
    })();
</script>
</body>
</html>
