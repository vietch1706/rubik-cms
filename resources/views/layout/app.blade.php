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
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #eff1f6;
            color: #4c4f69;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #fafafc;
            color: #4c4f69;
        }

        .navbar a, .navbar .nav-link {
            color: #4c4f69;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            left: 0;
            background-color: #e8e9f3;
            overflow-y: auto;
            transition: 0.3s;
            display: none;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            padding: 20px 0;
        }

        .active-sideitem {
            background-color: #ffe6fe;
            border-bottom-right-radius: 45%;
            border-top-right-radius: 45%;
            margin-right: 5px;
            display: inline-block;
        }

        .sidebar a {
            padding: 10px 20px;
            display: block;
            font-size: 16px;
            color: #4c4f69;
            text-decoration: none;
            transition: color 0.3s;
        }

        .sidebar a:hover {
            color: #c79c6e;
        }

        .sidebar a.active {
            background-color: #ffe6fe;
            color: #c79c6e;
            border-radius: 0 20px 20px 0;
        }

        .main-content {
            margin-left: 0;
            padding: 20px;
            transition: margin-left 0.3s;
        }

        .main-content.with-sidebar {
            margin-left: 250px;
        }

        .dropdown-item {
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: absolute;
                z-index: 1000;
                width: 100%;
            }

            .main-content.with-sidebar {
                margin-left: 0;
            }
        }
    </style>
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
                                <a class="dropdown-item" href="#" data-target="usersSidebar">Employees</a></li>
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
                            <i class="fas fa-cogs"></i> Products
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="productsDropdown">
                            <li><a class="dropdown-item" href="#" data-target="productsSidebar">Products</a></li>
                            <li><a class="dropdown-item" href="#" data-target="productsSidebar">Brands</a></li>
                            <li><a class="dropdown-item" href="#" data-target="productsSidebar">Categories</a></li>
                            <li><a class="dropdown-item" href="#" data-target="productsSidebar">Distributor</a></li>
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
    @if (Request::is('admin/users/*'))
        <div class="sidebar" id="usersSidebar" style="display: block;">
            @else
                <div class="sidebar" id="usersSidebar" style="display: none;">
                    @endif
                    <a href="{{ route('customers') }} "
                       @if(Request::route()->getName() == 'customers') class="active-sideitem" @endif>Customers</a>
                    <a href="">Employees</a>
                    <a href="">Roles</a>
                    <a href="">Permissions</a>
                </div>
                <div class="sidebar" id="productsSidebar" style="display: none;">
                    <a href="#">Products</a>
                    <a href="#">Brands</a>
                    <a href="#">Categories</a>
                    <a href="#">Distributor</a>
                </div>
                <div class="main-content" style="margin-left: 250px; padding: 25px 25px">
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
