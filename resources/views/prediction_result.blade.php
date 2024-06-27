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
<link rel="stylesheet" href="{{asset('assets/css/predict-result.css')}}">
    <h1 class="text-center">Prediction Result</h1>

  <!--      <div id="container">
            <h2 class="text-center">take a coffee while we process your image!</h2>
            <div class="coffee-corner m-auto">
            <div class="steam" id="steam1"> </div>
            <div class="steam" id="steam2"> </div>
            <div class="steam" id="steam3"> </div>
            <div class="steam" id="steam4"> </div>

            <div id="cup">
                <div id="cup-body">
                    <div id="cup-shade"></div>
                </div>
                <div id="cup-handle"></div>
            </div>
            <div id="shadow"></div>
        </div>
        </div>-->
    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    @if(isset($prediction))

    <div class="container full-height d-flex align-items-center my-5 justify-content-center">
        <div class="card col-md-6 text-center">
            <img src="{{ $imageUrl }}" style="width: 300px" class="card-img-top m-auto" alt="Uploaded Image">
            <div class="card-body">
                <h5 class="card-title">Prediction</h5>
                <p class="card-text">Predicted disease: {{ $prediction['predicted_class_label'] }}</p>
                <p class="card-text">Confidence: {{ $prediction['confidence'] }}</p>
            </div>
            <form action="{{ route('diagnosis.save') }}" method="POST">
                @csrf
                <input type="hidden" name="skin_image" value="{{ $imageUrl }}">
                <input type="hidden" name="diagnose" value="{{ $prediction['predicted_class_label']}}">
                <button type="submit" class="btn btn-primary">Save to Diagnosis History</button>
            </form>
        </div>
    </div>
    <div class="container text-center align-items-center my-5 justify-content-center">
    <a href="{{route('predict.form')}}" class="btn btn-primary text-center m-auto mb-3">check again <i class="fa-solid fa-rotate-right"></i></a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @endif




  <!--<script>
    document.addEventListener("DOMContentLoaded", function() {
    setTimeout(function() {
        var container = document.getElementById("container");
        var container2 = document.queryselectorall(".container");
        container.classList.add("hidden");
    }, 2000);
});
  </script>-->
@endif
@include('layouts.footer')
