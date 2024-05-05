@extends('layouts.app')
@extends('layouts.admin-navbar')
@section('content')
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

@endsection




