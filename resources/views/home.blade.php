@extends('layouts.app')

@if (Auth::check())
    @if (Auth::user()->status == 'pending')
        <div class="container text-center">
            <h1>Your medical license is under review</h1>
            <h1>Please wait for the admin to approve your account</h1>
        </div>
    @elseif (Auth::user()->status == 'declined')
        <div class="container text-center">
            <h1>Your medical license is declined</h1>
            <h1>Sorry, you are not a doctor</h1>
        </div>
    @else
        @extends('layouts.navbar')
        @section('content')
  
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                
                <div id="content" class="content content-full-width">
                    <div class="profile">
                        <div class="profile-content">
                            <div class="tab-content p-0">
                                <div class="tab-pane fade active show" id="profile-post">
                                    <ul class="timeline">
                                        @foreach($posts as $post)
                                            <li>
                                                <div class="timeline-time">
                                                    <a href="{{route("posts.show", $post->id)}}" class="date">{{ $post->created_at }}</a>
                                                </div>
                                                <div class="timeline-icon">
                                                    <a href="javascript:;">&nbsp;</a>
                                                </div>
                                                <div class="timeline-body">
                                                    <div class="timeline-header">
                                                        @if ($post->user->id ==  Auth::User()->id)
                                                            
                                                        
                                                        <form class="float-end" action="{{route("posts.destroy", $post->id)}}" method="post">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger">delete </button>
                                                        </form>
                                                        <a class="btn btn-warning float-end mx-3" href="{{route("posts.edit",$post->id)}}">edit</a>
                                                        @endif
                                                        <span class="userimage">
                                                            <img src="{{ asset('storage/'. $post->user->profile_picture)}}" alt="">
                                                        </span>
                                                        <span class="username">
                                                          <a href="{{route('users.show',$post->user->id)}}"> {{ $post->user->display_name }}  @if ($post->user->type=="doctor") <i class="fa-solid fa-user-doctor"></i> @endif </a> 
                                                        </span>
                                                    </div>
                                                    <div class="timeline-content">
                                                        <p>{{ $post->post_text }}</p>
                                                    </div>
                                                    <div class="timeline-content">
                                                        <img src="{{ asset("storage/".$post->attachments)}}" class="img-fluid" alt="">
                                                    </div>
                                                    <div class="timeline-footer">
                                                        <div class="row">
                                                        <div class="col-md-2">
                                                        <form action="{{route("posts.upvote", $post->id)}}" method="post">
                                                        @csrf
                                                        <button type="submit" class="btn btn-info">like {{$post->upvotes}}</button>
                                                        </form>
                                                    </div>
                                                    <div class="col-md-2">

                                                        <form action="{{route("posts.downvote", $post->id)}}" method="post">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger">dislike {{$post->downvotes}}</button>
                                                            </form>
                                                    </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            @foreach ($comments as $comment)
                                                            @if ($comment->post_id == $post->id)
                                                            <div class="timeline-header">
                                                                @if ($comment->user->id ==  Auth::User()->id)

                                                                <form class="float-end" action="{{route("comment.destroy", $comment->id)}}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-danger">delete</button>
                                                                    </form>
                                                                <a class="btn btn-warning float-end" href="{{route('comment.edit',$comment->id)}}">edit</a>
                                                                @endif
                                                                <span class="userimage">
                                                                    <img src="{{asset('storage/'.$comment->user->profile_picture)}}" alt="">
                                                                </span>
                                                                <span class="username">
                                                                    <a href="{{route('users.show',$comment->user->id)}}"> {{ $comment->user->display_name }}  @if ($comment->user->type=="doctor") <i class="fa-solid fa-user-doctor"></i> @endif </a>                                                                 </span>
                                                            </div>
                                                            <div class="timeline-content">
                                                                <p>{{ $comment->comment_content }}</p>
                                                            </div>
                                                            @endif
                                                            @endforeach

                                                        </div>
                                                        <div class="col-md-6">
                                                            <form action="{{ route('comment.store') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="post_id" value="{{ $post->id }}">


                                                                <div class="form-group">
                                                                    <label for="comment_content">make a comment</label>
                                                                    <input name="comment_content" class="form-control"  required>{{ old('comment_content') }}</input>                                                                    <button type="submit" class="btn btn-primary">Create</button>

                                                                    @error('comment_content')
                                                                        <div class="text-danger">{{ $message }}</div>
                                                                    @enderror

                                                                </div>
                                                            </form>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    @endsection
    @extends('layouts.footer')
@endif
@else
<!-- User is not authenticated, handle accordingly -->
@endif