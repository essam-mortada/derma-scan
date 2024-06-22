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
    <div class="col-md-6 m-auto my-5">


        <div class="card" >
            <div class="card-header" style="background-color: #1977cc; color:white">{{ __('edit post') }}</div>

            <div class="card-body">
        form action="{{ route('posts.update',$post->id) }}" method="POST" enctype="multipart/form-data">
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




        <button type="submit" class="btn mt-3 px-4"  style="background-color: #1977cc; color:white;" >edit</button>
    </form>
</div>
</div>
</div>
</div>

@include('layouts.footer')
@endif
