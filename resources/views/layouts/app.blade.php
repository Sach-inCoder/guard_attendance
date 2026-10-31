<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Guard Attendance System')</title>
    <link rel="stylesheet" href="{{asset('assets/css/main_guard.css')}}">
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css"
        integrity="sha512-QeR2VH+lsBE5LSAe1Q5EnTBbe7XTBubt8dG93Y7gidSgdMCr8nVqKcfKAMyN96SV8KDbZVTDXChatu5G2KQGzg=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    @stack('style')
</head>

<body class="bg-dark">
    <main class="main_guard_wrapper bg-light bg-gradient">
        <nav class="container py-2 shadow">
            <div class="row align-items-center">
                <div class="col-2">
                    <a href="{{ route('guard.dashboard') }}">
                        <img src="{{ asset('assets/images/favicon.png') }}" class="img-fluid" alt="">
                    </a>
                </div>
                <div class="col text-end">
                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#Id2"
                        aria-controls="Id2">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </nav>
        <div class="container py-2">
            {{-- Success message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Validation errors --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    {{-- <strong>Attendance failed!</strong> --}}

                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>

        <div class="offcanvas offcanvas-start" data-bs-backdrop="static" tabindex="-1" id="Id2"
            aria-labelledby="staticBackdropLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="staticBackdropLabel">
                    PSS - {{ auth()->user()->guardProfile?->employee_id ?? 'N/A' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <!-- Hover added -->
                <div class="list-group">
                    <a href="{{route('guard.dashboard')}}"
                        class="list-group-item list-group-item-action {{ request()->routeIs('guard.dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{route('my.attendance')}}"
                        class="list-group-item list-group-item-action {{ request()->routeIs('my.attendance') ? 'active' : '' }}">My
                        Attendance</a>
                    <a href="#" hidden class="list-group-item list-group-item-action disabled">Disabled item</a>
                </div>
                <hr>
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Do you want to logout?')"><i
                            class="fa-solid fa-person-walking-dashed-line-arrow-right"></i> LOGOUT</button>
                </form>
            </div>
        </div>
    </main>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $('form').on('submit',function (e) {
            e.preventDefault();
            const button = $(this).find('button[type=submit]');
            button.attr('disabled',true);
        })
    </script>
    @stack('scripts')
</body>

</html>