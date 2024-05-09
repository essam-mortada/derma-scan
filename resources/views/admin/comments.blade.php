@extends('layouts.app')
@extends('layouts.admin-navbar')
@section('content')
<form class="d-flex col-3" role="search" action="{{ route('comments.search') }}" method="GET">
  <input class="form-control me-2" type="text" name="query" placeholder="Search by post content" aria-label="Search">
  <button class="btn btn-outline-success" type="submit">Search</button>
</form>
<table class="table table--striped table-hover">
    <thead>
      <tr>
        <th scope="col">id</th>
        <th scope="col">comment content</th>
        <th scope="col">user</th>
        <th scope="col"></th>

      </tr>
    </thead>
    <tbody>
        @foreach ($comments as $comment)
      <tr>
        <th scope="row">{{$comment->id}}</th>
        <td>{{$comment->comment_content}}</td>
        <td>{{$comment->user->display_name}}</td>
        <td><form class="float-end" action="{{route("comments.delete", $comment->id)}}" method="post">
            @csrf
            <button type="submit" class="btn btn-danger">delete </button>
        </form>
        </td>

      </tr>
      @endforeach
    </tbody>

  </table>

@endsection




