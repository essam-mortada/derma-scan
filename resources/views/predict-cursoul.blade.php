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
@include("layouts.navbar")
<div class="container" >
    <div class="col-5 m-auto">
    <div id="cursoul" class="my-5">
        <div id="carouselExample" class="carousel slide">

            <div class="carousel-inner " >
              <div class="carousel-item active text-center">
                <img src="{{asset('assets/img/slider-1-1d065c59.svg')}}" class="d-block w-100" alt="...">
                <button class="btn btn-lg btn-primary  mt-2" data-bs-target="#carouselExample" data-bs-slide="next">got it</button>

              </div>
              <div class="carousel-item text-center " >
                <img src="{{asset('assets/img/slider-2-ab8f0d5d.svg')}}" class="d-block w-100" alt="...">
                <button class="btn btn-lg btn-primary mt-5" data-bs-target="#carouselExample" data-bs-slide="next" >got it</button>
            </div>
              <div class="carousel-item text-center">
                <img src="{{asset('assets/img/slider-3-c42624bb.svg')}}" class="d-block w-100" alt="...">
                <a href="{{route('predict.form')}}" class="btn btn-lg btn-primary mt-2">got it</a>
            </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
              <span  class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span  class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
    </div>
</div>
</div>
@include('layouts.footer')
@endif
