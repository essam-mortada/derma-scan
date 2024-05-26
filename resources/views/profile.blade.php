@extends("layouts.app")
@extends("layouts.navbar")
@section('content')


<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<main>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="top-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">profile</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        @if ($user->id==Auth::user()->id)            
        <a class="btn btn-primary float-end" href="{{route('users.edit',$user->id)}}">edit profile</a>
        <a class="btn btn-primary float-end" href="{{route('password.change.form', $user->id)}}">Change Password</a>

        @endif

        <div class="img mt-5" style="    background-image: linear-gradient(150deg, rgba(63, 174, 255, .3)15%, rgba(63, 174, 255, .3)70%, rgba(63, 174, 255, .3)94%),height: 350px;background-size: cover;"></div>
        <div class="card social-prof">
            <div class="card-body">
                <div class="wrapper">
                    <img src="{{asset('storage/'.$user->profile_picture)}}" alt="" class="user-profile">
                   
                    <h3>{{$user->name}}  @if ($user->type=="doctor") <i class="fa-solid fa-user-doctor text-primary"></i> @endif</h3>

                    <p>{{$user->type}}</p>
                </div>
                
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="h5 text-blue">{{'@'.$user->display_name}}</div>
                        <div class="h7 "><strong>Name :</strong> {{$user->name}}</div>
                        <div class="h7"><strong>About :</strong> {{$user->gender}}
                        </div>
                    </div>
                    
                </div>
          
            </div>
            <div class="col-lg-6 gedf-main">
                <!--- \\\\\\\Post-->
                <div class="card social-timeline-card">
                    <div class="card-header">
                        @if ($user->id == Auth::user()->id)
                            
                        
                        <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="posts-tab" data-toggle="tab" href="#posts" role="tab" aria-controls="posts" aria-selected="true">Share your insights</a>
                            </li>
                           
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="posts" role="tabpanel" aria-labelledby="posts-tab">
                               <form action="{{route('posts.store')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <div class="custom-file">
                                        <label class="custom-file-label" for="customFile">Upload image</label>

                                        <input type="file" name="attachments" class="form-control" id="customFile">
                                        @error('attachments')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    </div>
                                    <label class="sr-only" for="message">post</label>
                                    <textarea class="form-control" id="message" rows="3" name="post_text" placeholder="What are you thinking?"></textarea>
                                    @error('post_text')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                    <div class="form-group">
                                        <label for="post_type">Post Type</label>
                                        <select name="post_type" class="form-control" required>
                                            <option value="article">Article</option>
                                            <option value="question">Question</option>
                                        </select>
                                        @error('post_type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                            
                                    <div class="form-group">
                                        <label for="privacy">Privacy</label>
                                        <select name="privacy" class="form-control" required>
                                            <option value="public">Public</option>
                                            <option value="specialists_only">Specialists Only</option>
                                        </select>
                                        @error('privacy')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="btn-toolbar justify-content-between">
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-primary">share</button>
                                </div>
                              
                            </div>
                            </form>
                            </div>
                           
                        
                    </div>
                </div>
                @endif
                <!-- Post /////-->
                <!--- \\\\\\\Post-->
                @foreach ($posts as $post)
                    
                
                <div class="card social-timeline-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="mr-2">
                                    <img class="rounded-circle" width="45" src="{{asset('storage/'.$post->user->profile_picture)}}" alt="">
                                </div>
                                <div class="ml-2">
                                    <div class="h5 m-0 text-blue">{{$post->user->display_name}}  @if ($post->user->type=="doctor") <i class="fa-solid fa-user-doctor"></i> @endif</div>
                                </div>
                            </div>
    
                        </div>
                    </div>
                    <div class="card-body mb-5">
                        <div class="text-muted h7 mb-2"> <i class="fa fa-clock-o"></i>{{$post->created_at}}</div>
                        <a class="card-link" href="{{route('posts.show',$post->id)}}">
                            <h5 class="card-title">{{$post->post_text}}</h5>
                        </a>
                        <img src="{{asset('storage/'.$post->attachments)}}" alt="" class="img-fluid">

                    </div>
                    <div class="card-footer row">
                        <div class="col-md-2">
                            <form action="{{route("posts.upvote", $post->id)}}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-info">like {{$post->upvotes}}</button>
                            </form>
                        </div>
                            
                        <div class="col-md-3">

                            <form action="{{route("posts.downvote", $post->id)}}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-danger">dislike {{$post->downvotes}}</button>
                                </form>
                        </div>
                        <div class="box-footer box-comments" style="display: block;">
                            @foreach ($post->comments as $comment)
                                
                            
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
                          </div>                    </div>
                </div>
                @endforeach
                
            </div>
            
        </div>
    </div>
</main>
@endsection

@extends( 'layouts.footer' )