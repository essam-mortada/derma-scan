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
        <th scope="col">display name</th>
        <th scope="col">email</th>
        <th scope="col">medical license</th>
        <th scope="col">role</th>
        <th scope="col"></th>
        <th scope="col"></th>

      </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
      <tr>
        <th scope="row">{{$user->id}}</th>
        <td>{{$user->display_name}}</td>
        <td>{{$user->email}}</td>
        <td><img style="width: 60px" src="{{asset('storage/'.$user->medical_license)}}" alt="photo"></td>
        <td>{{$user->status}}</td>
        <td><form method="POST" action="{{ route('users.approve', $user->id) }}">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-success">Approve</button>
        </form>
        </td>
        <td><form method="POST" action="{{ route('users.decline', $user->id) }}">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-danger">Decline</button>
        </form></td>

      </tr>
      @endforeach
    </tbody>

  </table>


</div>


@endif
@endauth
