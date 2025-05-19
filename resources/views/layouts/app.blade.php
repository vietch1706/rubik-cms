@php
    use Illuminate\Support\Facades\Request;
@endphp
<!DOCTYPE html>
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
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        latte: {
                            rosewater: '#dc8a78',
                            flamingo: '#dd7878',
                            pink: '#ea76cb',
                            mauve: '#8839ef',
                            red: '#d20f39',
                            maroon: '#e64553',
                            peach: '#fe640b',
                            yellow: '#df8e1d',
                            green: '#40a02b',
                            teal: '#179299',
                            sky: '#04a5e5',
                            sapphire: '#209fb5',
                            blue: '#1e66f5',
                            lavender: '#7287fd',
                            text: '#4c4f69',
                            subtext1: '#5c5f77',
                            subtext0: '#6c6f85',
                            overlay2: '#7c7f93',
                            overlay1: '#8c8fa1',
                            overlay0: '#9ca0b0',
                            surface2: '#acb0be',
                            surface1: '#bcc0cc',
                            surface0: '#ccd0da',
                            base: '#eff1f5',
                            mantle: '#e6e9ef',
                            crust: '#dce0e8'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        [x-cloak] {
            display: none !important;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js" defer></script>
</head>

<body class="bg-latte-base font-sans text-latte-text" x-data="{
    sidebarOpen: window.innerWidth >= 1024,
    activeSidebar: '{{ Request::segment(2) }}Sidebar',
    activeDropdown: null,
    isMobileMenuOpen: false,

    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
    },

    toggleDropdown(dropdown) {
        this.activeDropdown = this.activeDropdown === dropdown ? null : dropdown;
    },

    closeSidebar() {
        if (window.innerWidth < 1024) {
            this.sidebarOpen = false;
        }
    },

    checkScreenSize() {
        this.sidebarOpen = window.innerWidth >= 1024;
    }
}" x-init="checkScreenSize();
window.addEventListener('resize', checkScreenSize)">

    @if (Request::route()->getName() != 'admin.login')
        <!-- Top Navigation Bar -->
        <nav class="bg-latte-mantle border-b border-latte-surface0 fixed w-full z-30 top-0">
            <div class="px-3 py-3 lg:px-5 lg:pl-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <!-- Mobile menu button -->
                        <button @click="toggleSidebar()"
                            class="p-2 rounded-md lg:hidden focus:outline-none focus:ring-2 focus:ring-latte-lavender">
                            <i class="fa-solid fa-bars text-latte-subtext0"></i>
                        </button>

                        <!-- Logo -->
                        <a href="{{ route('admin.dashboard') }}" class="flex ml-2 md:mr-24">
                            <img src="{{ asset('img/main-logo.png') }}" class="h-8 mr-3" alt="Logo" />
                            <span
                                class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-latte-text">Admin</span>
                        </a>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="flex items-center lg:hidden">
                        <button @click="isMobileMenuOpen = !isMobileMenuOpen"
                            class="p-2 text-latte-subtext0 rounded-md hover:bg-latte-surface0 focus:outline-none">
                            <i class="fa-solid" :class="isMobileMenuOpen ? 'fa-xmark' : 'fa-ellipsis-vertical'"></i>
                        </button>
                    </div>

                    <!-- Desktop Menu Items -->
                    <div class="hidden lg:flex items-center">
                        <div class="flex items-center space-x-2">
                            <!-- Notifications -->
                            <button class="p-2 text-latte-subtext0 rounded-lg hover:bg-latte-surface0">
                                <i class="fa-regular fa-bell"></i>
                            </button>

                            <!-- Profile Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                    class="flex items-center space-x-3 text-latte-text p-2 rounded-lg hover:bg-latte-surface0">
                                    <img src="{{ auth()->user()->avatar ?? asset('storage/avatars/default-avatar.png') }}"
                                        alt="Profile Picture"
                                        class="w-8 h-8 rounded-full object-cover border border-latte-surface1">
                                    <div class="flex flex-col text-left">
                                        <span class="text-sm font-medium">{{ auth()->user()->fullName }}</span>
                                        <span
                                            class="text-xs text-latte-subtext0">{{ auth()->user()->role ?? 'Administrator' }}</span>
                                    </div>
                                    <i class="fa-solid fa-chevron-down text-xs ml-1"></i>
                                </button>

                                <!-- Dropdown Menu -->
                                <div x-show="open" @click.outside="open = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                    <a href="{{ route('admin.users.profile.edit') }}"
                                        class="block px-4 py-2 text-sm text-latte-text hover:bg-latte-surface0">
                                        <i class="fa-solid fa-user-edit mr-2"></i> Edit Profile
                                    </a>
                                    <a href="{{ route('admin.users.profile.change_password') }}"
                                        class="block px-4 py-2 text-sm text-latte-text hover:bg-latte-surface0">
                                        <i class="fa-solid fa-key mr-2"></i> Change Password
                                    </a>
                                    <hr class="my-1 border-latte-surface0">
                                    <a href="#" onclick="logout()"
                                        class="block px-4 py-2 text-sm text-latte-red hover:bg-latte-surface0">
                                        <i class="fa-solid fa-sign-out-alt mr-2"></i> Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="lg:hidden" x-show="isMobileMenuOpen" x-cloak
                x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                <div class="px-2 pt-2 pb-3 space-y-1 bg-latte-mantle shadow-lg">
                    <a href="{{ route('admin.dashboard') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-latte-text hover:bg-latte-surface0">
                        <i class="fa-solid fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.users.profile.edit') }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-latte-text hover:bg-latte-surface0">
                        <i class="fa-solid fa-user-edit mr-2"></i> Profile
                    </a>
                    <a href="#" onclick="logout()"
                        class="block px-3 py-2 rounded-md text-base font-medium text-latte-red hover:bg-latte-surface0">
                        <i class="fa-solid fa-sign-out-alt mr-2"></i> Logout
                    </a>
                </div>
            </div>
        </nav>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed top-0 left-0 z-20 pt-16 h-screen transition-transform duration-300 bg-latte-crust border-r border-latte-surface0 w-64 lg:translate-x-0">
            <div class="py-4 px-3 h-full overflow-y-auto">
                <ul class="space-y-2">
                    <!-- Dashboard link -->
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center p-2 text-base font-normal rounded-lg hover:bg-latte-surface0 {{ Request::routeIs('admin.dashboard') ? 'bg-latte-surface0 text-latte-blue' : 'text-latte-text' }}">
                            <i class="fa-solid fa-tachometer-alt w-6 h-6 text-center"></i>
                            <span class="ml-3">Dashboard</span>
                        </a>
                    </li>

                    <!-- Users Dropdown -->
                    <li x-data="{ open: {{ Request::is('admin/users/*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full p-2 text-base font-normal rounded-lg hover:bg-latte-surface0 {{ Request::is('admin/users/*') ? 'bg-latte-surface0 text-latte-blue' : 'text-latte-text' }}">
                            <div class="flex items-center">
                                <i class="fa-solid fa-user-group w-6 h-6 text-center"></i>
                                <span class="ml-3">Users</span>
                            </div>
                            <i class="fa-solid" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                        </button>
                        <ul x-show="open" x-transition class="py-2 space-y-1 pl-10">
                            <li>
                                <a href="{{ route('admin.users.customers.index') }}"
                                    class="flex items-center p-2 text-sm font-normal rounded-lg hover:bg-latte-surface0 {{ Request::is('admin/users/customers*') ? 'bg-latte-surface0 text-latte-blue' : 'text-latte-text' }}">
                                    <i class="fa-solid fa-users w-4 h-4 text-center"></i>
                                    <span class="ml-3">Customers</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.employees.index') }}"
                                    class="flex items-center p-2 text-sm font-normal rounded-lg hover:bg-latte-surface0 {{ Request::is('admin/users/employees*') ? 'bg-latte-surface0 text-latte-blue' : 'text-latte-text' }}">
                                    <i class="fa-solid fa-briefcase w-4 h-4 text-center"></i>
                                    <span class="ml-3">Employees</span>
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center p-2 text-sm font-normal rounded-lg hover:bg-latte-surface0 text-latte-text">
                                    <i class="fa-solid fa-user-shield w-4 h-4 text-center"></i>
                                    <span class="ml-3">Roles</span>
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center p-2 text-sm font-normal rounded-lg hover:bg-latte-surface0 text-latte-text">
                                    <i class="fa-solid fa-unlock-alt w-4 h-4 text-center"></i>
                                    <span class="ml-3">Permissions</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Products Dropdown -->
                    <li x-data="{ open: {{ Request::is('admin/catalogs/*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full p-2 text-base font-normal rounded-lg hover:bg-latte-surface0 {{ Request::is('admin/catalogs/*') ? 'bg-latte-surface0 text-latte-blue' : 'text-latte-text' }}">
                            <div class="flex items-center">
                                <i class="fa-solid fa-cogs w-6 h-6 text-center"></i>
                                <span class="ml-3">Catalogs</span>
                            </div>
                            <i class="fa-solid" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                        </button>
                        <ul x-show="open" x-transition class="py-2 space-y-1 pl-10">
                            <li>
                                <a href="{{ route('admin.catalogs.products.index') }}"
                                    class="flex items-center p-2 text-sm font-normal rounded-lg hover:bg-latte-surface0 {{ Request::is('admin/catalogs/products*') ? 'bg-latte-surface0 text-latte-blue' : 'text-latte-text' }}">
                                    <i class="fa-solid fa-box w-4 h-4 text-center"></i>
                                    <span class="ml-3">Products</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.catalogs.brands.index') }}"
                                    class="flex items-center p-2 text-sm font-normal rounded-lg hover:bg-latte-surface0 {{ Request::is('admin/catalogs/brands*') ? 'bg-latte-surface0 text-latte-blue' : 'text-latte-text' }}">
                                    <i class="fa-solid fa-tag w-4 h-4 text-center"></i>
                                    <span class="ml-3">Brands</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.catalogs.categories.index') }}"
                                    class="flex items-center p-2 text-sm font-normal rounded-lg hover:bg-latte-surface0 {{ Request::is('admin/catalogs/categories*') ? 'bg-latte-surface0 text-latte-blue' : 'text-latte-text' }}">
                                    <i class="fa-solid fa-th-large w-4 h-4 text-center"></i>
                                    <span class="ml-3">Categories</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.catalogs.distributors.index') }}"
                                    class="flex items-center p-2 text-sm font-normal rounded-lg hover:bg-latte-surface0 {{ Request::is('admin/catalogs/distributors*') ? 'bg-latte-surface0 text-latte-blue' : 'text-latte-text' }}">
                                    <i class="fa-solid fa-truck w-4 h-4 text-center"></i>
                                    <span class="ml-3">Distributors</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Transactions Dropdown -->
                    <li x-data="{ open: {{ Request::is('admin/transactions/*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full p-2 text-base font-normal rounded-lg hover:bg-latte-surface0 {{ Request::is('admin/transactions/*') ? 'bg-latte-surface0 text-latte-blue' : 'text-latte-text' }}">
                            <div class="flex items-center">
                                <i class="fa-solid fa-clipboard-list w-6 h-6 text-center"></i>
                                <span class="ml-3">Transactions</span>
                            </div>
                            <i class="fa-solid" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                        </button>
                        <ul x-show="open" x-transition class="py-2 space-y-1 pl-10">
                            <li>
                                <a href="{{ route('admin.transactions.orders.index') }}"
                                    class="flex items-center p-2 text-sm font-normal rounded-lg hover:bg-latte-surface0 {{ Request::is('admin/transactions/orders*') ? 'bg-latte-surface0 text-latte-blue' : 'text-latte-text' }}">
                                    <i class="fa-solid fa-file-invoice w-4 h-4 text-center"></i>
                                    <span class="ml-3">Orders</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.transactions.receipts.index') }}"
                                    class="flex items-center p-2 text-sm font-normal rounded-lg hover:bg-latte-surface0 {{ Request::is('admin/transactions/import-receipts*') ? 'bg-latte-surface0 text-latte-blue' : 'text-latte-text' }}">
                                    <i class="fa-solid fa-file-import w-4 h-4 text-center"></i>
                                    <span class="ml-3">Import Receipts</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.transactions.invoices.index') }}"
                                    class="flex items-center p-2 text-sm font-normal rounded-lg hover:bg-latte-surface0 {{ Request::is('admin/transactions/invoices*') ? 'bg-latte-surface0 text-latte-blue' : 'text-latte-text' }}">
                                    <i class="fa-solid fa-file-invoice-dollar w-4 h-4 text-center"></i>
                                    <span class="ml-3">Invoices</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Campaigns Dropdown -->
                    <li x-data="{ open: {{ Request::is('admin/campaigns/*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full p-2 text-base font-normal rounded-lg hover:bg-latte-surface0 {{ Request::is('admin/campaigns/*') ? 'bg-latte-surface0 text-latte-blue' : 'text-latte-text' }}">
                            <div class="flex items-center">
                                <i class="fa-solid fa-percent w-6 h-6 text-center"></i>
                                <span class="ml-3">Campaigns</span>
                            </div>
                            <i class="fa-solid" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                        </button>
                        <ul x-show="open" x-transition class="py-2 space-y-1 pl-10">
                            <li>
                                <a href="{{ route('admin.campaigns.index') }}"
                                    class="flex items-center p-2 text-sm font-normal rounded-lg hover:bg-latte-surface0 {{ Request::is('admin/campaigns*') ? 'bg-latte-surface0 text-latte-blue' : 'text-latte-text' }}">
                                    <i class="fa-solid fa-bullhorn w-4 h-4 text-center"></i>
                                    <span class="ml-3">Campaigns</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Logs Dropdown -->
                    <li x-data="{ open: {{ Request::is('admin/logs/*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full p-2 text-base font-normal rounded-lg hover:bg-latte-surface0 {{ Request::is('admin/logs/*') ? 'bg-latte-surface0 text-latte-blue' : 'text-latte-text' }}">
                            <div class="flex items-center">
                                <i class="fa-solid fa-lines-leaning w-6 h-6 text-center"></i>
                                <span class="ml-3">Logs</span>
                            </div>
                            <i class="fa-solid" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                        </button>
                        <ul x-show="open" x-transition class="py-2 space-y-1 pl-10">
                            <li>
                                <a href="{{ route('admin.logs.index') }}"
                                    class="flex items-center p-2 text-sm font-normal rounded-lg hover:bg-latte-surface0 {{ Request::is('admin/logs*') ? 'bg-latte-surface0 text-latte-blue' : 'text-latte-text' }}">
                                    <i class="fa-solid fa-clock w-4 h-4 text-center"></i>
                                    <span class="ml-3">Logs</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Blogs Dropdown -->
                    <li x-data="{ open: {{ Request::is('admin/blogs/*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full p-2 text-base font-normal rounded-lg hover:bg-latte-surface0 {{ Request::is('admin/blogs/*') ? 'bg-latte-surface0 text-latte-blue' : 'text-latte-text' }}">
                            <div class="flex items-center">
                                <i class="fa-solid fa-book w-6 h-6 text-center"></i>
                                <span class="ml-3">Blogs</span>
                            </div>
                            <i class="fa-solid" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                        </button>
                        <ul x-show="open" x-transition class="py-2 space-y-1 pl-10">
                            <li>
                                <a href="{{ route('admin.blogs.index') }}"
                                    class="flex items-center p-2 text-sm font-normal rounded-lg hover:bg-latte-surface0 {{ Request::is('admin/blogs*') ? 'bg-latte-surface0 text-latte-blue' : 'text-latte-text' }}">
                                    <i class="fa-solid fa-newspaper w-4 h-4 text-center"></i>
                                    <span class="ml-3">Blogs</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <div :class="sidebarOpen ? 'lg:ml-64' : ''" class="p-4 mt-16 transition-all duration-300">
            <div class="p-4 bg-white rounded-lg shadow-sm border border-latte-surface0 min-h-[calc(100vh-7rem)]">
                @yield('content')
            </div>
        </div>
    @else
        <div class="min-h-screen bg-latte-base flex items-center justify-center">
            @yield('content')
        </div>
    @endif

    <!-- Scripts -->
    <script src="{{ asset('/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
    <script>
        function logout() {
            Swal.fire({
                title: 'Logout Confirmation',
                text: "Are you sure you want to logout?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1e66f5',
                cancelButtonColor: '#d20f39',
                confirmButtonText: 'Yes, logout'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('admin.logout') }}',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function() {
                            window.location.href = '{{ route('admin.login') }}';
                        },
                    });
                }
            });
        }

        // Initialize Select2 elements
        $(document).ready(function() {
            if ($.fn.select2) {
                $('.select2-element').select2({
                    theme: 'classic',
                    placeholder: "Select an option",
                    allowClear: true,
                    width: '100%'
                });
            }

            // Initialize TinyMCE if available
            if (typeof tinymce !== 'undefined') {
                tinymce.init({
                    selector: '.richtext-editor',
                    menubar: false,
                    toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | link image | bullist numlist | blockquote',
                    branding: false,
                    statusbar: false,
                    height: 300,
                    plugins: 'link image lists',
                    setup: function(editor) {
                        editor.on('init', function() {
                            editor.getDoc().body.style.fontFamily = 'Inter, sans-serif';
                        });
                    }
                });
            }
        });
    </script>
</body>

</html>
