
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Guard Attendance</title>
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
        body {
            min-height: 100dvh;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border: 0;
            border-radius: 16px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
        }

        .brand-icon {
            max-width: 60%;
            width: 120px;
            aspect-ratio: 1;
            display: grid;
            place-items: center;
            margin: 0 auto 16px;
            border-radius: 15px;
            background: #fff;
            color: #3730a3;
            font-size: 28px;
        }
    </style>
</head>

<body>
    <div class="container px-3">
        <div class="card login-card mx-auto">
            <div class="card-body p-4 p-md-5">

                <div class="text-center mb-4">
                    <div class="brand-icon">
                        <img src="{{ asset('assets/images/logo.jpg') }}" class="img-fluid" alt="Pro Shield Solutions">
                    </div>
                    <h3 class="fw-bold mb-1">Guard Attendance</h3>
                    <p class="text-secondary mb-0">
                        Sign in to your account
                    </p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.submit') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="mobile" class="form-label">
                            Registered Mobile Number
                        </label>

                        <input
                            type="number"
                            id="mobile"
                            name="mobile"
                            class="form-control form-control-lg"
                            value="{{ old('mobile') }}"
                            placeholder="Enter mobile number"
                            required
                            autofocus
                        >
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">
                            Password
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control form-control-lg"
                            placeholder="Enter password"
                            autocomplete="current-password"
                            required
                        >
                    </div>
                    <div class="mb-3">
                        <label>
                            <input
                                type="checkbox"
                                class="form-check-input"
                                name="remember"
                                @checked(old('remember'))
                                value="1">
                            Remember me
                        </label>
                    </div>
                    <button
                        type="submit"
                        class="btn btn-primary btn-lg w-100"
                    >
                        Sign In
                    </button>
                </form>

                <p class="text-center text-secondary small mt-4 mb-0">
                    Secure access for authorized personnel only.
                </p>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $("form").on('submit',function (e) {
            // e.preventDefault();
            $('button[type=submit]').attr('disabled','');
        })
    </script>
</body>
</html>
