<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="{{asset('assets/css/register.css')}}">
</head>
<body>
    <div class="register">
        <div class="row">
            <div class="col-md-3 register-left">
                <img src="{{asset('assets/img/register.svg')}}" alt=""/>
                <h3>Welcome</h3>
                <p>Please fill out the form to login!</p>
                <a href="{{route('register')}}" class="btn">Don't have an account? Sign up here</a>
            </div>
            <div class="col-md-9 register-right">

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <h3 class="register-heading">Login</h3>
                        <form action="{{route('login')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row register-form">
                                <div class="col-md-10">

                                    <div class="form-group">
                                        <input id="email" type="email" placeholder="Email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <a href="{{route('password.request')}}">forget password?</a>
                                    <div class="form-group">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btnRegister">login</button>
                                        </div>
                                    </div>
                                    <div>

                                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
