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
@include("layouts.navbar")

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('assets/css/profile.css')}}">
<main>
    <div class="container">
        <div class="row">
            <div class="col-md-12">

            </div>
        </div>
        <div class="img" style="    background-image: linear-gradient(150deg, rgba(63, 174, 255, .3)15%, rgba(63, 174, 255, .3)70%, rgba(63, 174, 255, .3)94%);height: 350px;background-size: cover;"></div>
        <div class="card social-prof">
            <div class="card-body">

                <div class="wrapper">
                    @if ($user->profile_picture==null && $user->gender== "male" )
                    <img src="{{asset('assets/img/male-profile.png')}}" alt="" class="user-profile">

                    @elseif ($user->profile_picture==null && $user->gender=="female")
                    <img src="{{asset('assets/img/female-profile.png')}}" alt="" class="user-profile">
                    @else
                    <img src="{{asset("storage/".$user->profile_picture)}}" alt="" class="user-profile">
                    @endif
                    <h3>{{$user->name}} @if ($user->type=='doctor')
                        <i class="fa-solid fa-user-doctor text-primary"></i>
                    @endif</h3>
                    <p>{{$user->type}}  </p>
                    @if (Auth::user()->id==$user->id)


                <div class="pull-right social-action dropdown">
                        <button class="btn  dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        </button>
                        <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="{{route('users.edit',$user->id)}}">Edit profile</a></li>
                          <li><a class="dropdown-item" href="{{route('password.change.form',$user->id)}}">Change password</a></li>
                        </li>
                        </ul>

                </div>
                @endif
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="h5 text-blue">@ {{$user->display_name}}</div>
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
                        @if ($user->id==Auth::user()->id)

                        <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="posts-tab" data-toggle="tab" href="#posts" role="tab" aria-controls="posts" aria-selected="true">Share your insights</a>
                            </li>
                            <li class="nav-item">
                            </li>
                        </ul>
                    </div>


                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade active show" id="posts" role="tabpanel" aria-labelledby="posts-tab">
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
                    @endif
                </div>

                <!-- Post /////-->
                <!--- \\\\\\\Post-->


                @foreach ($posts as $post )


                <div class="card social-timeline-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <div class="d-flex justify-content-between align-items-center ">
                                <div class="mr-2">
                                    @if ($post->user->profile_picture==null && $post->user->gender== "male" )
                                    <img  class="rounded-circle" width="45" src="{{asset('assets/img/male-profile.png')}}" alt="" >

                                    @elseif ($post->user->profile_picture==null && $post->user->gender=="female")
                                    <img  class="rounded-circle" width="45" src="{{asset('assets/img/female-profile.png')}}" alt="" >
                                    @else
                                    <img  class="rounded-circle" width="45" src="{{asset("storage/".$post->user->profile_picture)}}" alt="" >
                                    @endif                                </div>
                                <div class="ml-2">
                                    <div class="h5 m-0 text-blue">@ {{$post->user->display_name}}</div>
                                    <div class="h7 text-muted">{{$post->created_at}}</div>
                                </div>
                            </div>
                            <div>
                                @if (Auth::user()->id==$post->user->id)


                                <div class="pull-right social-action dropdown">
                                        <button class="btn  dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            ...
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
                    </div>
                    <div class="card-body">
                        <div class="text-muted h7 mb-2">{{$post->post_type}}</div>
                        <a class="card-link" href="{{route('posts.show',$post->id)}}">
                            <h5 class="card-title">{{$post->post_text}}</h5>
                        </a>
                        @if ($post->attachments)
                        <img src="{{asset("storage/".$post->attachments)}}" alt="" class="img-fluid">
                        @endif

                    </div>
                    <div style="display: flex" class="card-footer justify-content-left">

                        <form action="{{route('posts.upvote',$post->id)}}" method="POST"> @csrf <button type="submit" class="btn btn-white btn-xs"><i class="fa fa-thumbs-up text-primary"></i> Like {{$post->upvotes}}</button></form>

                        <form action="{{route('posts.downvote',$post->id)}}" method="POST"> @csrf <button type="submit" class="btn btn-white btn-xs"><i class="fa fa-thumbs-down text-danger"></i> dislike {{$post->downvotes}}</button></form>


                         <a href="{{route('posts.show',$post->id)}}" class="card-link mt-2"><i class="fa fa-comment"></i> Comment</a>

                    </div>

                </div>
                <!--- \\\\\\\Post-->
                @endforeach


            </div>

        </div>

    </div>


</main>

@include( 'layouts.footer' )
@endif
