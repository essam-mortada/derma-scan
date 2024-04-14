@extends('layouts.app')
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
                                                    <span class="date">{{ $post->created_at }}</span>
                                                </div>
                                                <div class="timeline-icon">
                                                    <a href="javascript:;">&nbsp;</a>
                                                </div>
                                                <div class="timeline-body">
                                                    <div class="timeline-header">
                                                        <span class="userimage">
                                                            <img src="{{ asset("storage/app/$post->user->profile_picture")}}" alt="">
                                                        </span>
                                                        <span class="username">
                                                            {{ $post->user->display_name }}
                                                        </span>
                                                    </div>
                                                    <div class="timeline-content">
                                                        <p>{{ $post->post_text }}</p>
                                                    </div>
                                                    <div class="timeline-content">
                                                        <img src="{{ asset("storage/app/$post->attachments")}}" class="img-fluid" alt="">
                                                    </div>
                                                    <div class="timeline-footer">
                                                        <a href="javascript:;" class="m-r-15 text-inverse-lighter">
                                                            <i class="fa fa-thumbs-up fa-fw fa-lg m-r-3"></i> Like {{$post->upvotes}}
                                                        </a>
                                                        <a  href="javascript:;" class="m-r-15 ml-5 text-inverse-lighter">
                                                            <i class="fa fa-thumbs-up fa-fw fa-lg m-r-3"></i> dislike {{$post->downvotes}}
                                                        </a>
                                                        <div class="col-md-6">
                                                            @foreach ($comments as $comment)
                                                            @if ($comment->post_id == $post->id)
                                                            <div class="timeline-header">
                                                                <span class="userimage">
                                                                    <img src="{{ asset("storage/app/$comment->user->profile_picture")}}" alt="">
                                                                </span>
                                                                <span class="username">
                                                                    {{ $comment->user->display_name }}
                                                                </span>
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
