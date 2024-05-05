@extends('layouts.app')
@extends('layouts.navbar')
@section('content')
<div class="container">
    <div class="col-md-6">

    
    <h1>edit Post</h1>
    <form action="{{ route('posts.update',$post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method( 'PUT' )
        <div class="form-group">
            <label for="post_text">Post Text</label>
            <input name="post_text" value="{{$post->post_text}}" class="form-control" rows="4" required>{{ old('post_text') }}</input>
            @error('post_text')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="attachments">Attachments</label>
            <input type="file" value="{{$post->attachments}}" name="attachments" class="form-control">
            @error('attachments')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

     

        <div class="form-group">
            <label for="privacy">Privacy</label>
            <select name="privacy"  class="form-control" required>
                <option value="public">Public</option>
                <option value="specialists_only">Specialists Only</option>
            </select>
            @error('privacy')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">edit</button>
    </form>
</div>
</div>
@endsection


@extends( 'layouts.footer' )