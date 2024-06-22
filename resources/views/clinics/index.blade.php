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
<div class="col-10 my-5" style="left: 0">
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Clinics') }}</div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        <a href="{{ route('clinics.create') }}" class="btn btn-primary mb-3">Add Clinic</a>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Speciality</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clinics as $clinic)
                                    <tr>
                                        <td>{{ $clinic->name }}</td>
                                        <td>{{ $clinic->location }}</td>
                                        <td>{{ $clinic->speciality }}</td>
                                        <td>
                                            <a href="{{ route('clinics.show', $clinic->id) }}" class="btn btn-info btn-sm mt-2">View</a>
                                            <a href="{{ route('clinics.edit', $clinic->id) }}" class="btn btn-warning btn-sm mt-2">Edit</a>
                                            <form action="{{ route('clinics.destroy', $clinic->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm mt-2">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
