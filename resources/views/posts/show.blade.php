@extends('layouts.app')
@extends('layouts.navbar')
@section('content')

<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<div class="container bootstrap snippets bootdey">
<div class="col-md-8">
  <div class="box box-widget">
    <div class="box-header with-border">
      <div class="user-block">
        @if ($post->user->id ==  Auth::User()->id)
                                                            
                                                        
        <form class="float-end" action="{{route("posts.destroy", $post->id)}}" method="post">
            @csrf
            <button type="submit" class="btn btn-danger">delete </button>
        </form>
        <a class="btn btn-warning float-end mx-3" href="{{route("posts.edit",$post->id)}}">edit</a>
        @endif
        <img class="img-circle" src="{{asset('storage/'.$post->user->profile_picture)}}" alt="User Image">
        <span class="username"><a href="{{route('users.show',$post->user->id)}}">{{$post->user->display_name}} @if ($post->user->type=="doctor") <i class="fa-solid fa-user-doctor"></i> @endif</a></span>
        <span class="description">{{$post->created_at}}</span>
      </div>
      
    </div>

    <div class="box-body" style="display: block;">
        @if ($post->attachments)
            
      <img style="width: 50%" class="img-responsive pad" src="{{$postImage}}" alt="Photo">
      @endif
      <p>{{ $post->post_text }}</p>
      <div class="row">
      <form action="{{route("posts.upvote", $post->id)}}" method="post">
        @csrf
        <button type="submit" class="btn btn-default btn-xs"><i class="fa fa-thumbs-o-up"></i> Like</button>
      </form>

      <form action="{{route("posts.downvote", $post->id)}}" method="post">
      <button type="submit" class="btn btn-default btn-xs"><i class="fa fa-thumbs-o-down"></i> disLike</button>
    </form>
    </div>
      <span class="pull-right text-muted">{{ $post->upvotes }} likes </span>
      <span class="pull-right text-muted mx-2">{{ $post->downvotes }} dislikes </span>

    </div>
    <div class="box-footer box-comments" style="display: block;">
        @foreach ($comments as $comment)
            
        
      <div class="box-comment">
        <img class="img-circle img-sm" src="{{asset("storage/".$comment->user->profile_picture)}}" alt="User Image">
        <div class="comment-text">
            <span class="username"><a href="{{route('users.show',$comment->user->id)}}">{{$comment->user->display_name}} @if ($comment->user->type=="doctor") <i class="fa-solid fa-user-doctor"></i> @endif</a></span>

          <span class="text-muted pull-right">{{$comment->created_at}}</span>
          </span>
          {{$comment->comment_content}}
        </div>
      </div>
      @endforeach
    </div>
    <div class="box-footer" style="display: block;">
      <form action="{{route('comment.store')}}" method="post">
        @csrf
        <input type="hidden" name="post_id" value="{{ $post->id }}">
        <img class="img-responsive img-circle img-sm" src="{{asset('storage/'.Auth::user()->profile_picture)}}" alt="Alt Text">
        <div class="img-push row">
          <input type="text" name="comment_content" class="form-control input-sm " placeholder="make comment">{{ old('comment_content') }}</input>
          @error('comment_content')
          <div class="text-danger">{{ $message }}</div>
        @enderror
        </div>
      </form>
    </div>
  </div>
</div>
</div>
@endsection

@extends( 'layouts.footer' )