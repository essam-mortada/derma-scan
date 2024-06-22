<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Derma Scan</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{asset('assets/img/favicon.png')}}" rel="icon">
  <link href="{{asset('assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/aos/aos.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{asset('assets/css/main.css')}}" rel="stylesheet">


</head>

<body class="index-page">

  <header id="header" class="header sticky-top">



    <div class="branding d-flex align-items-center">

      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="{{route('home')}}" class="logo d-flex align-items-center me-auto">
          <!-- Uncomment the line below if you also wish to use an image logo -->
          <!-- <img src="assets/img/logo.png" alt=""> -->
          <h1 class="sitename">Derma Scan</h1>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="{{route('home')}}" class="active">Home<br></a></li>
            @guest


            <li><a href="#about">About</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#departments">common diseases</a></li>

            <li><a href="#contact">Contact</a></li>
            @endguest
            @auth
            <li class="dropdown"><a href="#"> Appointments</a>
                <ul>
                    <li><a href="{{route('appointments.from')}}">Make an Appointment</a></li>
                    <li><a href="{{route('appointments.user',Auth::user()->id)}}">My Appointments</a></li>

                </ul>
            </li>
            <li><a href="{{route('community')}}">community</a></li>
            <li><a href="{{route('chatbot')}}">Chatbot</a></li>
            <li><a href="{{route('users.show',Auth::user()->id)}}">profile</a></li>
            @endauth
            <li class="dropdown"><a href="#"> <i style="font-size: 25px" class="bi bi-person-fill"></i></a>
                <ul>
                    @guest
                  <li><a href="{{route('login')}}">sign in</a></li>
                  <li><a href="{{route('register')}}">sign up</a></li>
                  @endguest
                  @auth
                  <li>
                    <form action="{{route('logout')}}" method="POST">
                        @csrf
                        <button class="btn btn-sm"  type="submit">logout</button>

                    </form>
                </li>

                  @endauth
                </ul>
            </li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <a class="cta-btn d-none d-sm-block" href="{{route('predict.index')}}">Skin Check</a>

      </div>

    </div>

  </header>
