<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg w-100">
        <div class="container-fluid">
            <a class="navbar-brand d-flex d-lg-none" href="./">
                <span class="fs-4 text-primary">Loomi</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <div class="d-flex flex-column justify-content-between align-items-center w-100 flex-wrap">
                    @include('includes.nav')
                    @include('includes.search')
                </div>
            </div>
        </div>
    </nav>
</header>