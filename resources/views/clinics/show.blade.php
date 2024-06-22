@auth


@if (Auth::user()->type=='user' || Auth::user()->type=='doctor')
<link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
<div class="container text-center">
    <img width="60%" src="{{asset('assets/img/not-found.jpg')}}" alt="">
    <form action="{{route('logout')}}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
</div>
@else
<div class="row">
    <div class="col-2">
@include('layouts.admin-navbar')
</div>
<div class="col-10 my-4" style="left: 0">
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Clinic Details') }}</div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Name:</th>
                                <td>{{ $clinic->name }}</td>
                            </tr>
                            <tr>
                                <th>Location:</th>
                                <td>{{ $clinic->location }}</td>
                            </tr>
                            <tr>
                                <th>Speciality:</th>
                                <td>{{ $clinic->speciality }}</td>
                            </tr>
                        </table>
                        <a href="{{ route('clinics.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
</div>
@endif
@endauth
