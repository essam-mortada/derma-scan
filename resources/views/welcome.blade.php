@extends('layouts.app')
<body>
    <div class="container">
        <h1>Welcome to the application!</h1>
        <div class="row mt-5 text-center justify-content-center">
           
            <div class="col-md-4">
                <a href="{{ route('login') }}" class="btn btn-primary btn-block">Login as User</a>
            </div>
        </div>
    </div>
</body>
</html>