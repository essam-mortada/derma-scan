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
    <h1>Prediction Result</h1>

        <div id="container">
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
        </div>
    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    @if(isset($prediction))
        <p>Predicted Class Label: {{ $prediction['predicted_class_label'] }}</p>
        <p>Confidence: {{ $prediction['confidence'] }}</p>
    @endif

    <a href="{{ url()->previous() }}">Go Back</a>


  <script>
    document.addEventListener("DOMContentLoaded", function() {
    setTimeout(function() {
        var container = document.getElementById("container");
        container.classList.add("hidden");
    }, 2000);
});
  </script>
@endif
