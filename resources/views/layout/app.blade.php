@php use Illuminate\Support\Facades\Request; @endphp
    <!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-touch-icon.png') }}">
    <link rel="icon" type="image/x-icon" sizes="32x32" href="{{ asset('img/favicon-32x32.ico') }}">
    <link rel="icon" type="image/x-icon" sizes="16x16" href="{{ asset('img/favicon-16x16.ico') }}">
    <link rel="manifest" href="{{ asset('img/site.webmanifest') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" type="text/css"/>
</head>
<body>
@if(Request::route()->getName() != 'login')
    <nav class="navbar navbar-expand-lg navbar-light p-0">
        <div class="container-fluid p-0">
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
                            <li>
                                <a class="dropdown-item" href="{{ route('receipts') }}"
                                   data-target="transactionsSidebar">Import Receipts</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('invoices') }}"
                                   data-target="transactionsSidebar">Invoices</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="campaignsDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-percent"></i> Campaigns
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="campaignsDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('campaigns') }}" data-target="campaignsSidebar">Campaigns</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="logsDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-list"></i> Logs
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="logsDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('logs') }}" data-target="logsSidebar">Logs</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 20px">
                            <img src="{{ auth()->user()->avatar ?? asset('storage/avatars/default-avatar.png') }}"
                                 alt="Profile Picture"
                                 class="rounded-circle"
                                 style="width: 25px; height: 25px; object-fit: cover;"> Profile
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="fas fa-user-edit"></i> Edit Profile
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item logout" href="#" onclick="logout()">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div id="usersSidebar" @class(['sidebar', 'd-block' => Request::is('admin/users/*')])>
        <a href="{{ route('customers') }}"
            @class(['active-sideitem' => Request::is('admin/users/customers/*', 'admin/users/customers')])>Customers</a>
        <a href="{{ route('employees') }}"
            @class(['active-sideitem' => Request::is('admin/users/employees/*', 'admin/users/employees')])>Employees</a>
        <a href="">Roles</a>
        <a href="">Permissions</a>
    </div>
    <div id="productsSidebar" @class(['sidebar', 'd-block' => Request::is('admin/catalogs/*')])>
        <a href="{{ route('brands') }}"
            @class(['active-sideitem' => Request::is('admin/catalogs/brands/*', 'admin/catalogs/brands')])>Brands</a>
        <a href="{{ route('categories') }}"
            @class(['active-sideitem' => Request::is('admin/catalogs/categories/*', 'admin/catalogs/categories')])>Categories</a>
        <a href="{{ route('distributors') }}"
            @class(['active-sideitem' => Request::is('admin/catalogs/distributors/*', 'admin/catalogs/distributors')])>Distributors</a>
        <a href="{{ route('products') }}"
            @class(['active-sideitem' => Request::is('admin/catalogs/products/*', 'admin/catalogs/products')])>Products</a>
    </div>
    <div id="transactionsSidebar" @class(['sidebar', 'd-block' => Request::is('admin/transactions/*')])>
        <a href="{{ route('orders') }}"
            @class(['active-sideitem' => Request::is('admin/transactions/orders/*', 'admin/transactions/orders')])>Orders</a>
        <a href="{{ route('receipts') }}"
            @class(['active-sideitem' => Request::is('admin/transactions/import-receipts/*', 'admin/transactions/import-receipts')])>Import
            Receipts</a>
        <a href="{{ route('invoices') }}"
            @class(['active-sideitem' => Request::is('admin/transactions/invoices/*', 'admin/transactions/invoices')])>Invoices</a>
    </div>
    <div id="campaignsSidebar" @class(['sidebar', 'd-block' => Request::is('admin/campaigns/*')])>
        <a href="{{ route('campaigns') }}"
            @class(['active-sideitem' => Request::is('admin/campaigns/campaigns/*', 'admin/campaigns/campaigns')])>Campaigns</a>
    </div>
    <div id="logsSidebar" @class(['sidebar', 'd-block' => Request::is('admin/logs/*')])>
        <a href="{{ route('logs') }}"
            @class(['active-sideitem' => Request::is('admin/logs/logs/*', 'admin/logs/logs')])>Logs</a>
    </div>
    <div id="profileSidebar" @class(['sidebar', 'd-block' => Request::is('admin/users/profile')])>
        <a href="{{ route('profile') }}"
            @class(['active-sideitem' => Request::is('admin/users/profile/*', 'admin/users/profile')])>Edit
            Profile</a>
        <a href="#" onclick="logout()" class="logout">
            Logout
        </a>
    </div>
    <div class="main-content"
         style="margin: 75px 0 0 {{ Request::is('admin') ? '0px' : '240px' }}; padding: 25px">
        @yield('content')
    </div>
@else
    <div class="main-content" style="margin: 0; padding: 0">
        @yield('content')
    </div>
@endif
<script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/sweetalert2@11.js') }}"></script>
<script src="{{ asset('/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('js/select2.full.min.js') }}"></script>
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
    $(document).ready(function () {
        $('.logout').on('click', function (e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route('logout') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function () {
                    window.location.href = '{{ route('login') }}';
                },
            });
        });
    });
</script>
</body>
</html>
