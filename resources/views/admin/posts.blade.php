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

    <div style="margin-top: 80px" class="mb-3" >
        <form class="d-flex col-3 m-auto" role="search" action="{{ route('posts.search') }}" method="GET">
          <input class="form-control me-2" type="text" name="query" placeholder="Search by post content" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
        </div>
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

</div>



@endif
@endauth
