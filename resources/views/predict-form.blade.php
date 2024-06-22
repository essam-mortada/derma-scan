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
<link rel="stylesheet" href="{{asset('assets/css/upload-image.css')}}">
<body>
    <style>
    .drop-container {
        position: relative;
        display: flex;
        gap: 10px;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 200px;
        padding: 20px;
        border-radius: 10px;
        border: 2px dashed #555;
        color: #444;
        cursor: pointer;
        transition: background .2s ease-in-out, border .2s ease-in-out;
      }

      .drop-container:hover {
        background: #eee;
        border-color: #111;
      }

      .drop-container:hover .drop-title {
        color: #222;
      }

      .drop-title {
        color: #444;
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        transition: color .2s ease-in-out;
      }
</style>
    <!-- partial:index.partial.html -->

        <div class="modal-body col-md-6 m-auto text-center">
            <h2 class="modal-title">Upload your skin image</h2>
            <p class="modal-description">Attach the file below</p>
            <form action="{{route('predict')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="images" class="drop-container" id="dropcontainer">
                    <span class="drop-title">Upload image here</span>
                    
                    <input class="form-control" type="file" id="images" accept="image/*" required>
                </label>
                <div class="modal-footer col-md-6 m-auto">
                    <button type="submit" class="btn-primary form-control ">Upload File</button>
                </div>
            </form>
        </div>
           <!-- partial -->
</body>
@include('layouts.footer')
@endif
