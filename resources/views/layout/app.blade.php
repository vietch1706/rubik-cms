@php use Illuminate\Support\Facades\Request; @endphp
    <!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-touch-icon.png') }}">
    <link rel="icon" type="image/x-icon" sizes="32x32" href="{{ asset('img/favicon-32x32.ico') }}">
    <link rel="icon" type="image/x-icon" sizes="16x16" href="{{ asset('img/favicon-16x16.ico') }}">
    <link rel="manifest" href="{{ asset('img/site.webmanifest') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/list.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"/>


</head>
<body>
@if(Request::route()->getName() != 'login')
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ asset('img/main-logo.png') }}" alt="Store Logo" height="50">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="usersDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-users"></i> Users
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="usersDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('customers') }}" data-target="usersSidebar">Customers</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('employees') }}" data-target="usersSidebar">Employees</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-target="usersSidebar">Roles</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-target="usersSidebar">Permissions</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="productsDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-box"></i> Products
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="productsDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('products') }}" data-target="productsSidebar">Products</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('brands') }}" data-target="productsSidebar">Brands</a>

                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('categories') }}" data-target="productsSidebar">Categories</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('distributors') }}"
                                   data-target="productsSidebar">Distributors</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="transactionsDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-list-check"></i> Transactions
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="transactionsDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('orders') }}" data-target="transactionsSidebar">Orders</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-user-circle"></i> Profile
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="sidebar" id="usersSidebar" @if (Request::is('admin/users/*')) style="display: block;"
         @else style="display: none;" @endif>
        <a href="{{ route('customers') }}"
           @if(Request::is('admin/users/customers/*', 'admin/users/customers')) class="active-sideitem" @endif>Customers</a>
        <a href="{{ route('customers') }}" @class(['active-sideitem' => Request::is('admin/users/customers/*', 'admin/users/customers')])>Customers</a>
        <a href="{{ route('employees') }}"
           @if(Request::is('admin/users/employees/*', 'admin/users/employees')) class="active-sideitem" @endif>Employees</a>
        <a href="">Roles</a>
        <a href="">Permissions</a>
    </div>
    <div class="sidebar" id="productsSidebar" @if (Request::is('admin/catalogs/*')) style="display: block;"
         @else style="display: none;" @endif>
        <a href="{{ route('products') }}"
           @if(Request::is('admin/catalogs/products/*', 'admin/catalogs/products')) class="active-sideitem" @endif>Products</a>
        <a href="{{ route('brands') }}"
           @if(Request::is('admin/catalogs/brands/*', 'admin/catalogs/brands')) class="active-sideitem" @endif>Brands</a>
        <a href="{{ route('categories') }}"
           @if(Request::is('admin/catalogs/categories/*', 'admin/catalogs/categories')) class="active-sideitem" @endif>Categories</a>
        <a href="{{ route('distributors') }}"
           @if(Request::is('admin/catalogs/distributors/*', 'admin/catalogs/distributors')) class="active-sideitem" @endif>Distributors</a>
    </div>
    <div class="sidebar" id="transactionsSidebar" @if (Request::is('admin/transactions/*')) style="display: block;"
         @else style="display: none;" @endif>
        <a href="{{ route('orders') }}"
           @if(Request::is('admin/transactions/orders/*', 'admin/transactions/orders')) class="active-sideitem" @endif>Orders</a>
    </div>
    <div class="main-content" style="margin: 60px 0 0 250px; padding: 25px 25px">
        @yield('content')
    </div>
@else
    <div class="main-content" style="margin: 0; padding: 0">
        @yield('content')
    </div>
@endif
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function toggleSidebar(targetId) {
        const sidebar = document.getElementById(targetId);
        const mainContent = document.querySelector('.main-content');

        document.querySelectorAll('.sidebar').forEach((el) => {
            el.style.display = 'none';
        });

        if (sidebar) {
            sidebar.style.display = 'block';
            mainContent.classList.add('with-sidebar');
        }
    }

    document.querySelectorAll('.dropdown-item').forEach((item) => {
        item.addEventListener('click', function (e) {
            const targetId = e.target.getAttribute('data-target');
            toggleSidebar(targetId);
        });
    });
</script>
</body>
</html>
