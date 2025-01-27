<!doctype html>

<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    @yield('title')

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('TTCOA.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/remixicon/remixicon.css') }}" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Toasts -->
    <script src="{{ asset('assets/js/ui-toasts.js') }}"></script>

    <!-- Page CSS -->
    @yield('styles')

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
        integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body #toast-container>div {
            opacity: 1;
        }
    </style>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <i class="ri-file-edit-fill fs-3 text-primary"></i>
                    <a href="{{ route('/') }}" class="app-brand-link">
                        <span class="app-brand-text demo menu-text fw-semibold ms-1">Requisitions</span>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboards -->

                    @can('view-procurement-requisitions')
                        <li class="menu-header">
                            <span class="menu-header-text">Menu</span>
                        </li>
                        <li @class(['menu-item', 'active' => request()->routeIs('/')])>
                            <a href="{{ route('/') }}" class="menu-link">
                                <i class="menu-icon tf-icons ri-home-smile-line"></i>
                                <div data-i18n="Basic">Dashboard</div>
                            </a>
                        </li>

                        <li @class([
                            'menu-item',
                            'active' => request()->routeIs('requisitions.*'),
                        ])>
                            <a href="{{ route('requisitions.index') }}" class="menu-link">
                                <i class="menu-icon ri-file-edit-line"></i>
                                <div data-i18n="Basic">Requisitions</div>
                            </a>
                        </li>
                    @endcan

                    @can('view-users-page')
                        <li @class(['menu-item', 'active' => request()->routeIs('users')])>
                            <a href="{{ route('users') }}" class="menu-link">
                                <i class="menu-icon ri-user-line"></i>
                                <div data-i18n="Basic">Users</div>
                            </a>
                        </li>
                    @endcan

                    @can('view-cost-budgeting-requisitions')
                        <li class="menu-header mt-7">
                            <span class="menu-header-text">Cost &amp; Budgeting</span>
                        </li>
                        <!-- Apps -->
                        <li @class([
                            'menu-item',
                            'active' => request()->routeIs('cost_and_budgeting.*'),
                        ])>
                            <a href="{{ route('cost_and_budgeting.index') }}" class="menu-link">
                                <i class="menu-icon ri-file-edit-line"></i>
                                <div data-i18n="Basic">Requisitions</div>
                            </a>
                        </li>
                    @endcan

                    @can('view-accounts-payable-requisitions')
                        <li class="menu-header mt-7">
                            <span class="menu-header-text">Accounts Payable</span>
                        </li>
                        <!-- Apps -->
                        <li @class([
                            'menu-item',
                            'active' => request()->routeIs('accounts_payable.*'),
                        ])>
                            <a href="{{ route('accounts_payable.index') }}" class="menu-link">
                                <i class="menu-icon ri-file-edit-line"></i>
                                <div data-i18n="Basic">Requisitions</div>
                            </a>
                        </li>
                    @endcan

                    @can('view-vote-control-requisitions')
                        <li class="menu-header mt-7">
                            <span class="menu-header-text">Vote Control</span>
                        </li>
                        <!-- Apps -->
                        <li @class([
                            'menu-item',
                            'active' => request()->routeIs('vote_control.*'),
                        ])>
                            <a href="{{ route('vote_control.index') }}" class="menu-link">
                                <i class="menu-icon ri-file-edit-line"></i>
                                <div data-i18n="Basic">Requisitions</div>
                            </a>
                        </li>
                    @endcan

                    @can('view-check-room-requisitions')
                        <li class="menu-header mt-7">
                            <span class="menu-header-text">Check Staff</span>
                        </li>
                        <!-- Apps -->
                        <li @class(['menu-item', 'active' => request()->routeIs('check_room.*')])>
                            <a href="{{ route('check_room.index') }}" class="menu-link">
                                <i class="menu-icon ri-file-edit-line"></i>
                                <div data-i18n="Basic">Requisitions</div>
                            </a>
                        </li>
                    @endcan


                    @can('view-cheque-processing-requisitions')
                        <li class="menu-header mt-7">
                            <span class="menu-header-text">Cheque Processing</span>
                        </li>
                        <!-- Apps -->
                        <li @class([
                            'menu-item',
                            'active' => request()->routeIs('cheque_processing.*'),
                        ])>
                            <a href="{{ route('cheque_processing.index') }}" class="menu-link">
                                <i class="menu-icon ri-file-edit-line"></i>
                                <div data-i18n="Basic">Requisitions</div>
                            </a>
                        </li>
                    @endcan

                    <li class="menu-header mt-7">
                        <span class="menu-header-text">Help</span>
                    </li>
                    <li @class(['menu-item mb-3', 'active' => request()->routeIs('help')])>
                        <a href="{{ route('help') }}" class="menu-link">
                            <i class="menu-icon fa-solid fa-circle-question"></i>
                            <div data-i18n="Basic">User Manual</div>
                        </a>
                    </li>

                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                            <i class="ri-menu-fill ri-24px"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

                        <ul class="navbar-nav flex-row align-items-center ms-auto">

                            <li class="nav-item lh-1 me-4">
                                <a class="github-button" href="#" data-icon="octicon-star" data-size="large"
                                    data-show-count="true"
                                    aria-label="Star themeselection/materio-bootstrap-html-admin-template-free on GitHub">{{ Auth::user()->name }}</a>
                            </li>

                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        @if (Auth::user()->role->name == 'Super Admin')
                                            <img src="{{ asset('assets/img/avatars/vader.png') }}" alt
                                                class="w-px-40 h-auto rounded-circle" />
                                        @else
                                            <i class="fa-regular fa-circle-user fs-2"></i>
                                        @endif
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end mt-3 py-2">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="avatar avatar-online">
                                                        @if (Auth::user()->role->name == 'Super Admin')
                                                            <img src="{{ asset('assets/img/avatars/vader.png') }}" alt
                                                                class="w-px-40 h-auto rounded-circle" />
                                                        @else
                                                            <i class="fa-regular fa-circle-user fs-2"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0 small">{{ Auth::user()->name }}</h6>
                                                    <small class="text-muted">{{ Auth::user()->department }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="d-grid px-4 pt-2 pb-1">
                                            <a class="btn btn-danger d-flex" href="{{ route('logout') }}">
                                                <small class="align-middle">Logout</small>
                                                <i class="ri-logout-box-r-line ms-2 ri-16px"></i>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <!-- / Content -->


                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Page JS -->
    {{-- <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script> --}}


    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))


        window.addEventListener('show-alert', event => {
            var message = event.detail.message;

            // Display an alert with the received message
            alert(message);
        })

        window.addEventListener('show-message', event => {

            toastr.options = {
                "progressBar": true,
                "closeButton": true,
            }
            toastr.success(event.detail.message, '', {
                timeOut: 3000
            });
        })
        window.addEventListener('scroll-to-top', event => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        })
    </script>


    @if (Session::has('error'))
        <script>
            toastr.options = {
                "progressBar": true,
                "closeButton": true,
            }
            toastr.error("{{ Session::get('error') }}", '', {
                timeOut: 6000
            });
        </script>
    @endif


    @if (Session::has('success'))
        <script>
            toastr.options = {
                "progressBar": true,
                "closeButton": true,
            }
            toastr.success("{{ Session::get('success') }}", '', {
                timeOut: 3000
            });
        </script>
    @endif

    @yield('scripts')

</body>

</html>
