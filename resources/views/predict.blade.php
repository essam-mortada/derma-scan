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
<!-- Hero 5 - Bootstrap Brain Component -->
<link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/utilities/bsb-overlay/bsb-overlay.css">
<link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/utilities/bsb-btn-size/bsb-btn-size.css">
<link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/heroes/hero-6/assets/css/hero-6.css">
      <div class="row" style="height: 98vh">
        <div class="container">
        <div class="col-12">
          <div  class="container-fluid bsb-hero-6 bsb-overlay " style="--bsb-overlay-opacity: 0.4; --bsb-overlay-bg-color: var(--bs-light-rgb); background-image: url('{{asset('assets/img/skin.png')}}');">
            <div class="row justify-content-md-center align-items-center">
              <div class="col-12 col-md-11 col-xl-10">
                <h2 class="display-1 text-center text-md-start text-shadow-head fw-bold mb-4">check your skin!</h2>
                <p class="lead text-center text-md-start text-shadow-body mb-5 d-flex justify-content-sm-center justify-content-md-start">
                  <span class="col-12 col-sm-10 col-md-8 col-xxl-7">Check your skin on the smartphone and get instant results within 1 minute.</span>
                </p>
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center justify-content-md-start">
                  <a href="{{route('predict.cursoul')}}" class="btn bsb-btn-2xl "
                  style="background-color: #2c4964; color:white ">Check Now</a>
                </div>
                <img src="{{asset('assets/img/mole-41f22223.webp')}}" alt="">

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


@include('layouts.footer')
@endif
