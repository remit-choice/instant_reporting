<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Login</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ public_path('assets/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ public_path('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ public_path('assets/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ public_path('assets/dist/css/alt/login.css') }}">

    <script src="{{ public_path('assets/dist/js/pages/login.js')}}"></script>
    <script>
        setTimeout(function() {
            $('#failed').slideUp('slow');
            $('#UpdatedSuccess').slideUp('slow');
            $('#email_msg').slideUp('slow');
            $('#password_msg').slideUp('slow');
        }, 3000);
    </script>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="/admin" class="h1"><b>Instant Reporting</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in</p>
                <form action="/admin" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <input type="email" name="email" class="form-control" placeholder="Email">
                        @error('email')
                        <div class="invalid-feedback order-last" id="email_msg">
                            {{ $message }}
                        </div>
                        @enderror
                        @if (session('failed'))
                        <div class="invalid-feedback order-last" id="email_msg">
                            {{ session('failed') }}
                        </div>
                        @endif
                        @if (session('UpdatedSuccess'))
                        <div class="invalid-feedback order-last" id="email_msg">
                            <span class="text-success error_message text-center to" style="margin-top: -5px"
                                id="UpdatedSuccess">
                                {{ session('UpdatedSuccess') }}
                            </span>
                        </div>
                        @endif
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <input type="password" name="password" class="form-control" id="password"
                            placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                {{-- <span class="fas fa-eye"></span> --}}
                                <span class="me-2 d-flex" id="eye1" onclick="ForPasswer()">
                                    <i id="Show" class="far fa-eye"></i>
                                    <i id="Hide" class="fas fa-eye-slash"></i>
                                </span>
                            </div>
                        </div>
                        @error('password')
                        <div class="invalid-feedback order-last" id="password_msg">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="row my-2">
                        <div class="col-6">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" value="RememberMe" checked>
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <!-- <div class="col-6 text-right">
                            <a href="forgot-password.html">Forget Password?</a>
                        </div> -->
                        <!-- /.col -->
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                    <div class="row my-2">
                        <div class="col-6">
                            <!-- <div class="icheck-primary">
                                <input type="checkbox" id="remember" value="RememberMe" checked>
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div> -->
                        </div>
                        <!-- /.col -->
                        <div class="col-6 text-right">
                            <a href="forgot-password.html">Forget Password?</a>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <!-- /.social-auth-links -->
                {{-- <p class="my-4">
                    <a href="register.html" class="text-center">Not have any account?Registered Here</a>
                </p> --}}
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ public_path('assets/plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ public_path('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{ public_path('assets/dist/js/adminlte.min.js')}}"></script>
</body>

</html>