@include('layouts.navbar')

<div class="col-md-6 m-auto">
    <div class="card my-5" >
        <div class="card-header" style="background-color: #1977cc; color:white">{{ __('edit comment') }}</div>

        <div class="card-body">
    <form action="{{ route('comment.update',$comment->id) }}" method="POST">
        @csrf
        @method( 'PUT' )

        <div class="form-group">
            <label for="comment_content">Edit comment</label>
            <input name="comment_content" value="{{$comment->comment_content}}" class="form-control "  required>{{ old('comment_content') }}</input>


            @error('comment_content')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            <button type="submit" class="btn btn-primary mt-3">edit</button>
        </div>
    </form>
</div>
    </div>
</div>

@include( 'layouts.footer' )
