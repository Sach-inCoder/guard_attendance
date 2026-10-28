<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Guard Attendance System')</title>
    <link rel="stylesheet" href="{{asset('assets/css/main_admin.css')}}">
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css"
        integrity="sha512-QeR2VH+lsBE5LSAe1Q5EnTBbe7XTBubt8dG93Y7gidSgdMCr8nVqKcfKAMyN96SV8KDbZVTDXChatu5G2KQGzg=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    @stack('style')
</head>

<body class="">
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="admin-logo" hidden>
                <a href="{{route('admin.dashboard')}}">
                    <img class="img-fluid" src="{{ asset('assets/images/favicon.png') }}" width="50"
                        alt="Pro Shield Solution">
                </a>
            </div>
            <nav class="mt-3">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.guards.index') }}"
                    class="nav-link {{ request()->routeIs('admin.guards.index') ? 'active' : '' }}">
                    Guards
                </a>
                <a href="{{ route('admin.companies.index') }}"
                    class="nav-link {{ request()->routeIs('admin.companies.index') ? 'active' : '' }}">
                    Companies
                </a>
                <a href="{{ route('admin.sites.index') }}"
                    class="nav-link {{ request()->routeIs('admin.sites.index') ? 'active' : '' }}">
                    Sites
                </a>
            </nav>
        </aside>
        <main class="admin-main">
            <header class="admin-header">
                <div>
                    <button class="btn btn-primary d-lg-none menuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <strong>
                        @yield('page-title', 'Dashboard')
                    </strong>
                </div>
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        {{ auth()->user()->name }}
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>
                            <span class="dropdown-item-text">
                                {{ auth()->user()->email }}
                            </span>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item" onclick="return confirm('Do you want to logout?')">
                                    <i class="fa-solid fa-person-walking-dashed-line-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>

                </div>
            </header>
            <div class="admin-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">

                        {{ session('success') }}

                        <button type="button" class="btn-close" data-bs-dismiss="alert">
                        </button>

                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert">
                        </button>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $('.menuToggle').on('click', function () {
            $('aside').toggleClass('show');
            $('main').toggleClass('show');
        })
    </script>
    @stack('scripts')
</body>

</html>