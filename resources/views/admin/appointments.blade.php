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
<div class="col-10 " style="left: 0; margin-top:100px">

<table class="table table--striped table-hover">
    <thead>
      <tr>
        <th scope="col">id</th>
        <th scope="col">User</th>
        <th scope="col">Clinic</th>
        <th scope="col">Appointment date</th>
        <th scope="col"></th>

      </tr>
    </thead>
    <tbody>
        @foreach ($appointments as $appointment)
      <tr>
        <th scope="row">{{$appointment->id}}</th>
        <td>{{$appointment->user->name}}</td>
        <td>{{$appointment->clinic->name}}</td>
        <td>{{$appointment->date}}</td>
        <td><form class="float-end" action="{{route("appointments.delete", $appointment->id)}}" method="post">
            @csrf
            <button type="submit" class="btn btn-danger">delete </button>
        </form>
        </td>

      </tr>
      @endforeach
    </tbody>

  </table>

</div>


@endif
@endauth
