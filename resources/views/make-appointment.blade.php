@if (Auth::user()->status=='pending' || Auth::user()->status=='declined')
<link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
<div class="container text-center">
    <img width="60%" src="{{asset('assets/img/not-found.jpg')}}" alt="">
    <form action="{{route('logout')}}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
</div>
@else
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
@include('layouts.navbar')
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-5">
                    <div class="card-header" style="background-color: #1977cc; color:white;">{{ __('Book Appointment') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('appointments.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="clinic_id">Clinic</label>
                                <select id="clinic_id" class="form-control @error('clinic_id') is-invalid @enderror" name="clinic_id" required>
                                    <option value="" disabled selected>Select a clinic</option>
                                    @foreach($clinics as $clinic)
                                        <option value="{{ $clinic->id }}">{{ $clinic->name }} </option>
                                    @endforeach
                                </select>
                                @error('clinic_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="date">Appointment Date</label>
                                <input id="date" type="datetime-local" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ old('date') }}" required>
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" style="background-color: #1977cc; color:white;" class="btn">Book Appointment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
@include('layouts.footer')
@endif
