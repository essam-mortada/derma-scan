@extends('layouts.app')
@extends('layouts.navbar')
@section('content')

<div class="col-md-6">
    <form action="{{ route('comment.update',$comment->id) }}" method="POST">
        @csrf
        @method( 'PUT' )

        <div class="form-group">
            <label for="comment_content">Edit comment</label>
            <input name="comment_content" value="{{$comment->comment_content}}" class="form-control"  required>{{ old('comment_content') }}</input>                                                                  
              

            @error('comment_content')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            <button type="submit" class="btn btn-primary">edit</button>
        </div>
    </form>
</div>
@endsection
@extends( 'layouts.footer' )