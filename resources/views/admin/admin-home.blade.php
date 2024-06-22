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
    <div class="container text-center">
        <h1>welcome {{Auth::user()->name}} !</h1>
        <img style="height: auto; width:600px" src="{{asset('assets/img/admin-home.jpg')}}" alt="">
    </div>
</div>
@endif
@endauth
