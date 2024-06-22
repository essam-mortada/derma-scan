@auth


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
<style>
.notification {
background-color: white;
  color: #1977cc;
  position: relative;
  display: inline-block;
  border-radius: 50px ;
  border: none
}

.notification:hover {
  scale: 1.2;
  transition: 0.3s
}

.notification .badge {
  position: absolute;
  top: -10px;
  right: -10px;
  padding: 5px 10px;
  border-radius: 50%;
  background: red;
  color: white;
}
</style>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('assets/css/community.css')}}">
<div class="container ">
    <div class="row ">
        <div class="col-md-1 my-2 text-center">
            <div class="dropdown my-2">
                <button class="btn notification " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-bell" style="font-size: 25px"></i>
                    @if ($notifications->count()>0)
                  <span class="badge">{{$notifications->count()}}</span>
                  @endif
                </button>
                <ul class="dropdown-menu">
                    @if ($notifications->count()==0)
                    <li class="dropdown-item">No notifications</li>
                    @endif
                    @foreach ($notifications as $notification )
                  <li> <form action="{{route('notifications.markAsRead')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{$notification->id}}">
                    <button type="submit" class="dropdown-item" href="#">{{$notification->message}}</button></form>
                </li>
                  @endforeach

                </ul>
              </div>
        </div>

</div>
    <div class="card col-md-3 m-auto my-2 mr-1 text-center"  style="  box-shadow: 0 2px 4px rgba(190, 190, 190, 0.5), 0 4px 8px rgba(0, 0, 0, 0.2);
    border-radius:15px 15px; overflow:hidden; background:#1977cc">
            <div class="card-body" >
                <!-- Button trigger modal -->
                    <button type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="width: 100% ;color:white; background-color:#1977cc">
                        <i class="fa-solid fa-plus"></i>  New post
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">New post</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>


                        <div class="card-body">
                            <div class="tab-content" id="myTabContent">
                                    <form action="{{route('posts.store')}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                    <div class="form-group">
                                        <textarea name="post_text" placeholder="what's in your mind?" class="form-control" rows="4" required>{{ old('post_text') }}</textarea>
                                        @error('post_text')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mt-2">
                                        <input type="file" name="attachments" class="form-control">
                                        @error('attachments')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mt-2">
                                        <select name="post_type" class="form-control" required>
                                            <option selected value="article">select post type</option>
                                            <option value="article">Article</option>
                                            <option value="question">Question</option>
                                        </select>
                                        @error('post_type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">post</button>

                                </div>
                            </form>
                            </div>

                        </div>
                            </div>

                        </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <div class="row">

    @foreach ($posts as $post )
<div class="col-md-7 m-auto">

    <div class="social-feed-box" style="  box-shadow: 0 2px 4px rgba(190, 190, 190, 0.5), 0 4px 8px rgba(0, 0, 0, 0.2);
    border-radius:15px 15px; overflow:hidden;
">
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
        <div class="social-avatar">
            <a href="{{route('users.show',$post->user->id)}}" class="pull-left">
                @if ($post->user->profile_picture==null && $post->user->gender== "male" )
                <img src="{{asset('assets/img/male-profile.png')}}" alt="" >

                @elseif ($post->user->profile_picture==null && $post->user->gender=="female")
                <img src="{{asset('assets/img/female-profile.png')}}" alt="" >
                @else
                <img src="{{asset("storage/".$post->user->profile_picture)}}" alt="" >
                @endif
            </a>
            <div class="media-body">
                <a href="{{route('users.show',$post->user->id)}}">
                    {{$post->user->display_name}} @if ($post->user->type=='doctor')
                    <i class="fa-solid fa-user-doctor text-primary"></i>
                @endif
                </a>
               <a href="{{route('posts.show',$post->id)}}"> <small class="text-muted">{{ $post->created_at }}</small></a>
            </div>
        </div>
        <div class="social-body">
            <p>
                {{$post->post_text}}
            </p>
            @if ($post->attachments)


            <div class="text-center">
            <a style="cursor: pointer;" id="imgblur" onclick="toggleBlurred()" class="blurred" ><img  style="width: 450px; height:200" src="{{asset("storage/".$post->attachments)}}" class="img-responsive"></a>
            </div>
            @endif
            <div class="btn-group">
                <form action="{{route('posts.upvote',$post->id)}}" method="POST"> @csrf <button type="submit" class="btn btn-white btn-xs"><i class="fa fa-thumbs-up text-primary"></i> {{$post->upvotes}} Like </button></form>
                <form action="{{route('posts.downvote',$post->id)}}" method="POST"> @csrf <button type="submit" class="btn btn-white btn-xs"><i class="fa fa-thumbs-down text-danger"></i> {{$post->downvotes}} dislike</button>
                <a href="{{route('posts.show',$post->id)}}" class="btn btn-white btn-xs"><i class="fa fa-comments"></i> Comment</a>
            </div>
        </div>
        <div class="social-footer">
            @foreach ($post->comments as $comment)

            <div class="social-comment">
                @if (Auth::user()->id==$comment->user->id)


                <div class="pull-right social-action dropdown">
                        <button class="btn  dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">

                        </button>
                        <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="{{route('comment.edit',$comment->id)}}">Edit</a></li>
                          <li> <form action="{{route('comments.delete',$comment->id)}}" method="POST">
                            @csrf
                            <button class="dropdown-item"  type="submit">Delete</button>

                        </form>
                        </li>
                        </ul>

                </div>
                @endif
                <a href="" class="pull-left">
                    @if ($comment->user->profile_picture==null && $comment->user->gender== "male" )
                    <img src="{{asset('assets/img/male-profile.png')}}" alt="" >

                    @elseif ($comment->user->profile_picture==null && $comment->user->gender=="female")
                    <img src="{{asset('assets/img/female-profile.png')}}" alt="" >
                    @else
                    <img src="{{asset("storage/".$comment->user->profile_picture)}}" alt="" >
                    @endif
                </a>
                <div class="media-body">
                    <a href="#">
                        {{$comment->user->display_name}} @if ($comment->user->type=='doctor')
                        <i class="fa-solid fa-user-doctor text-primary"></i>
                    @endif
                    </a>
                    {{$comment->comment_content}}
                    <br>
                    <small class="text-muted">{{$comment->created_at}}</small>
                </div>

            </div>
            @endforeach
            <div class="social-comment">

                <div class="media-body">
                    <form action="{{route('comment.store')}}" method="POST">
                        <div class="input-group">
                            <input type="text" class="form-control input-sm" name="comment_content" placeholder="write a comment">
                            <input type="hidden" name="post_id" value="{{$post->id}}">

                          </div>
                     @csrf
                    </form>
                </div>
            </div>




        </div>

    </div>

</div>
@endforeach

</div>
</div>
</div>
<script>
document.querySelectorAll('.blurred').forEach(function(image) {
    image.addEventListener('click', function() {
        this.classList.toggle('blurred');
    });
});
</script>

@include('layouts.footer')
@endif
@endauth
