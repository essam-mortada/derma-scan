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
@include('layouts.navbar')
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('assets/css/post.css')}}">

<div class="container bootstrap snippets bootdey mt-5 ">
<div class="col-md-8 m-auto ">
  <div class="box box-widget">
    <div class="box-header with-border">
      <div class="user-block">
        @if ($post->user->profile_picture==null && $post->user->gender== "male" )
                <img src="{{asset('assets/img/male-profile.png')}}" alt="" >

                @elseif ($post->user->profile_picture==null && $post->user->gender=="female")
                <img src="{{asset('assets/img/female-profile.png')}}" alt="" >
                @else
                <img src="{{asset("storage/".$post->user->profile_picture)}}" alt="" >
                @endif
        <span class="username"><a href="#">{{$post->user->display_name}}</a></span>
        <span class="description">{{$post->created_at}}</span>
      </div>
      <div class="box-tools">
        @if (Auth::user()->id==$post->user->id)
        <div class="pull-right social-action dropdown">
                <button class="btn  dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">

                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="{{route('posts.edit',$post->id)}}">Edit</a></li>
                  <li> <form action="{{route('posts.delete',$post->id)}}" method="POST">
                    @csrf
                    <button class="dropdown-item"  type="submit">Delete</button>

                </form>
                </li>
                </ul>

        </div>
        @endif
      </div>
    </div>
    <div class="box-body">
      <p>{{$post->post_text}}</p>


      @if ($post->attachments!=null)
      <div class="attachment-block clearfix text-center">

        <img class="attachment-img" src="{{asset("storage/".$post->attachments)}}" alt="Attachment Image">

      </div>
      @endif
      <div class="row">
        <div class="col-lg-2 col-4">
            <form action="{{route('posts.upvote',$post->id)}}" method="POST">@csrf
              <button type="submit" class="btn btn-default btn-xs"><i class="fa fa-thumbs-o-up"></i> like {{$post->upvotes}}</button>
            </form>
          </div>
        <div class=" col-lg-2  col-5">
          <form action="{{route('posts.downvote',$post->id)}}" method="POST">@csrf
            <button type="submit" class="btn btn-default btn-xs"><i class="fa fa-thumbs-o-down"></i> dislike {{$post->downvotes}}</button>
          </form>
        </div>

      </div>
    </div>
    </div>
@foreach ($post->comments as $comment)

    <div class="box-footer box-comments">
      <div class="box-comment">
        @if ($comment->user->profile_picture==null && $comment->user->gender== "male" )

        <img class="img-circle img-sm"src="{{asset('assets/img/male-profile.png')}}" alt="" >

        @elseif ($comment->user->profile_picture==null && $comment->user->gender=="female")
        <img class="img-circle img-sm" src="{{asset('assets/img/female-profile.png')}}" alt="" >
        @else
        <img class="img-circle img-sm" src="{{asset("storage/".$comment->user->profile_picture)}}" alt="" >
        @endif
        <div class="comment-text">
          <span class="username">
          {{$comment->user->display_name}}<br>
          <span class="text-muted" style="font-size: 10px">{{$comment->created_at}}</span>
          </span>
         {{$comment->comment_content}}
        </div>
      </div>

    </div>
    @endforeach
    <div class="box-footer mb-5">
      <form action="{{route('comment.store')}}" method="POST">
        @csrf
        @if (Auth::user()->profile_picture==null && Auth::user()->gender== "male" )

        <img class="img-responsive img-circle img-sm"src="{{asset('assets/img/male-profile.png')}}" alt="" >

        @elseif (Auth::user()->profile_picture==null && Auth::user()->gender=="female")
        <img class="img-responsive img-circle img-sm" src="{{asset('assets/img/female-profile.png')}}" alt="" >
        @else
        <img class="img-responsive img-circle img-sm" src="{{asset("storage/".Auth::user()->profile_picture)}}" alt="" >
        @endif
        <div class="img-push">
          <input type="text" name="comment_content" class="form-control  input-sm" placeholder="write a comment">
          <input type="hidden" name="post_id" value="{{$post->id}}">
        </div>
      </form>
    </div>
  </div>
</div>
</div>

@include( 'layouts.footer' )
@endif
