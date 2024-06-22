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
@include('layouts.admin-navbar')
<link rel="stylesheet" href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}">
@if (isset($query))
    <h2>Search results for: {{ $query }}</h2>
@endif
<table class="table table--striped table-hover">
    <thead>
      <tr>
        <th scope="col">id</th>
        <th scope="col">post content</th>
        <th scope="col">user</th>
        <th scope="col">attachments</th>
        <th scope="col"></th>

      </tr>
    </thead>
    <tbody>
        @foreach ($posts as $post)
      <tr>
        <th scope="row">{{$post->id}}</th>
        <td>{{$post->post_text}}</td>
        <td>{{$post->user->display_name}}</td>
        <td><img style="width: 60px" src="{{asset('storage/'.$post->attachments)}}" alt="photo"></td>
        <td><form class="float-end" action="{{route("posts.delete", $post->id)}}" method="post">
            @csrf
            <button type="submit" class="btn btn-danger">delete </button>
        </form>
        </td>

      </tr>
      @endforeach
    </tbody>

  </table>


@endif
@endauth


