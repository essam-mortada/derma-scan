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
<div class="col-10" style="left: 0">
<div style="margin-top: 110px" class="mb-3 justify-content-center" >
<form class="d-flex col-3 m-auto" role="search" action="{{ route('users.search') }}" method="GET">
    <input class="form-control me-2" type="text" name="query" placeholder="Search by display_name" aria-label="Search">
    <button class="btn btn-outline-success" type="submit">Search</button>
  </form>
    </div>
<table class="table table--striped table-hover">
    <thead>
      <tr>
        <th scope="col">id</th>
        <th scope="col">display name</th>
        <th scope="col">email</th>
        <th scope="col">profile picture</th>
        <th scope="col">role</th>
        <th scope="col"></th>

      </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
      <tr>
        <th scope="row">{{$user->id}}</th>
        <td>{{$user->display_name}}</td>
        <td>{{$user->email}}</td>
        <td><img style="width: 60px" src="{{asset('storage/'.$user->profile_picture)}}" alt="photo"></td>
        <td>{{$user->type}}</td>
        <td><form class="float-end" action="{{route("user.delete", $user->id)}}" method="post">
            @csrf
            <button type="submit" class="btn btn-danger">delete </button>
        </form>
        </td>

      </tr>
      @endforeach
    </tbody>

  </table>

</div>



</div>
@endif
@endauth
