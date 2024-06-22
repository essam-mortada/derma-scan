<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
                <p>Please fill out the form to create an account!</p>
                <a href="{{route('login')}}" class="btn">Already have an account? Sign in here</a>
            </div>
            <div class="col-md-9 register-right">

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <h3 class="register-heading">Register</h3>
                        <form action="{{route('register')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row register-form">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input id="name" type="text" placeholder="Name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
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
                                    <div class="form-group">
                                        <input id="password-confirm" type="password" placeholder="Confirm Password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                    <div class="form-group">
                                        <input id="display_name" type="text" placeholder="Display Name" class="form-control @error('display_name') is-invalid @enderror" name="display_name" value="{{ old('display_name') }}" required autocomplete="display_name" autofocus>
                                        @error('display_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select id="gender" class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                                            <option value="" disabled selected>Select your gender</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        @error('gender')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <select id="type" class="form-control @error('type') is-invalid @enderror" name="type" required>
                                            <option value="" disabled selected>Select your role</option>
                                            <option value="user" {{ old('type') == 'user' ? 'selected' : '' }}>User</option>
                                            <option value="doctor" {{ old('type') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                                        </select>
                                        @error('type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="profile_picture">Profile Picture</label>
                                        <input id="profile_picture" type="file" class="form-control @error('profile_picture') is-invalid @enderror" name="profile_picture">
                                        @error('profile_picture')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group" id="medical_license_div">
                                        <label for="medical_license">Medical License</label>
                                        <input id="medical_license" type="file" class="form-control @error('medical_license') is-invalid @enderror" name="medical_license" value="{{ old('medical_license') }}" autocomplete="medical_license" autofocus>
                                        @error('medical_license')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btnRegister">Register</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <h3 class="register-heading">Additional Information</h3>
                        <!-- Add additional profile information form here if needed -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
