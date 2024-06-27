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
<link rel="stylesheet" href="{{asset('assets/css/appointments.css')}}">
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-white mb-5">
                <div class="card-heading clearfix border-bottom mb-4">
                    <h4 class="card-title" style="color: #2c4964;">Your diagnosis history</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach ($diagnosis as $diagnose )

                        <li class="position-relative booking">
                            <div class="media">
                                <div class="mb-3" style="width: 200px">
                                    <img src="{{$diagnose->skin_image}}" alt="">
                                </div>
                                <div class="media-body">
                                    <h5 class="mb-4">{{$diagnose->diagnose}} </h5>
                                    <div class="mb-3">
                                        <span class="mr-2 d-block d-sm-inline-block mb-2 mb-sm-0"> Date:</span>
                                        <span class="bg-light-blue">{{$diagnose->created_at}}</span>
                                    </div>


                                </div>
                            </div>

                        </li>

                        @endforeach

                    </ul>

                </div>
            </div>

        </div>
    </div>
    </div>
@include('layouts.footer')
@endif
