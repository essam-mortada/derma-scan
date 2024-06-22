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

<div class="container">
    <div class="col-md-6">
<h1>Create Post</h1>
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="post_text">Post Text</label>
            <textarea name="post_text" class="form-control" rows="4" required>{{ old('post_text') }}</textarea>
            @error('post_text')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="attachments">Attachments</label>
            <input type="file" name="attachments" class="form-control">
            @error('attachments')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

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

        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>
</div>
@endsection


@include( 'layouts.footer' )
@endif
