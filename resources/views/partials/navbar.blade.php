<nav class="navbar navbar-expand-lg shadow-sm bg-white">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="/">AcortadorURL</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('top-urls') }}"><i class="bi bi-bar-chart-line-fill"></i> Más visitadas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-none" id="btn-my-urls" href="/my-urls"><i class="bi bi-link-45deg"></i> Mis URLs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/"><i class="bi bi-plus-circle"></i> Nueva URL</a>
                </li>
            </ul>

            <div class="d-flex">
                <a href="{{ route('login') }}" id="btn-login" class="btn btn-primary-custom me-2">
                    <i class="bi bi-box-arrow-in-right"></i> Iniciar sesión
                </a>
                <a href="{{ route('login') }}" id="btn-register" class="btn btn-register me-2">
                    <i class="bi bi-person-plus"></i> Registrarse
                </a>
                <a href="#" id="btn-logout" class="btn btn-secondary-custom d-none">
                    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                </a>
            </div>
        </div>
    </div>
</nav>
