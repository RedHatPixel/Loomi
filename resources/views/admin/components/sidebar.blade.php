{{-- Toggle button for < md --}}
<button class="btn btn-dark position-fixed top-0 end-0 m-2 d-lg-none" type="button" 
    data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar" style="z-index: 999;">
    <i class="bi bi-list"></i>
</button>

{{-- Offcanvas Sidebar for < md screens --}}
<div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="offcanvasSidebar">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title text-primary">Loomi Admin</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.products') }}"><i class="bi bi-box-seam me-2"></i> Products</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.orders') }}"><i class="bi bi-receipt me-2"></i> Orders</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.sales') }}"><i class="bi bi-graph-up me-2"></i> Sales</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.users') }}"><i class="bi bi-people me-2"></i> Users</a></li>
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST">@csrf
                    <button type="submit" class="nav-link text-danger"><i class="bi bi-box-arrow-left"></i> Sign Out</button>
                </form>
            </li>
        </ul>
    </div>
</div>

{{-- Sidebar for md+ screens --}}
<nav id="sidebarMenu" class="d-none d-lg-block bg-dark text-white vh-100 position-sticky top-0" 
    style="width:220px; flex-shrink:0;">
    <div class="pt-3">
        <h5 class="px-3 mb-3 d-none d-lg-block">
            <p class="nav-link text-primary">Loomi Admin</p>
        </h5>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    <span class="d-none d-lg-inline ms-2">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center" href="{{ route('admin.products') }}">
                    <i class="bi bi-box-seam"></i>
                    <span class="d-none d-lg-inline ms-2">Products</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center" href="{{ route('admin.orders') }}">
                    <i class="bi bi-receipt"></i>
                    <span class="d-none d-lg-inline ms-2">Orders</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center" href="{{ route('admin.sales') }}">
                    <i class="bi bi-graph-up"></i>
                    <span class="d-none d-lg-inline ms-2">Sales</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white d-flex align-items-center" href="{{ route('admin.users') }}">
                    <i class="bi bi-people"></i>
                    <span class="d-none d-lg-inline ms-2">Users</span>
                </a>
            </li>
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST">@csrf
                    <button type="submit" class="nav-link text-danger d-flex align-items-center">
                        <i class="bi bi-box-arrow-left"></i>
                        <span class="d-none d-lg-inline ms-2">Sign Out</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>
