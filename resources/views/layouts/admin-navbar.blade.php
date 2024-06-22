<!--Main Navigation-->
<link rel="stylesheet" href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/fontawesome-free/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/admin-nav.css')}}">
<header>
    <!-- Sidebar -->
    <nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-white">
      <div class="position-sticky">
        <div class="list-group list-group-flush mx-3 mt-4">
          <a href="{{route('home')}}" class="list-group-item list-group-item-action py-2 ripple" aria-current="true">
            <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>Main dashboard</span>
          </a>
          <a href="{{route('admin.users')}}" class="list-group-item list-group-item-action py-2 ripple ">
            <i class="fa-solid fa-users me-3"></i><span>users</span>
          </a>
          <a href="{{route('admin.posts')}}" class="list-group-item list-group-item-action py-2 ripple"><i
             class="fa-regular fa-address-card me-3"></i><span>posts</span></a>
          <a href="{{route('admin.comments')}}" class="list-group-item list-group-item-action py-2 ripple"><i
             class="fa-regular fa-comments me-3"></i><span>comments</span></a>
          <a href="{{route('admin.doctor-requests')}}" class="list-group-item list-group-item-action py-2 ripple">
            <i class="fa-solid fa-person-circle-question me-3"></i><span>Doctor requests</span>
          </a>
          <div class="dropdown">
            <a style="border: none; cursor:pointer;" class="list-group-item list-group-item-action py-2 ripple"  data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-house-medical me-3"></i><span>clinics</span>
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{route('clinics.index')}}">all clinics</a></li>
              <li><a class="dropdown-item" href="{{route('clinics.create')}}">Add clinic</a></li>
            </ul>
          </div>
          <a href="{{route('add-admin')}}" class="list-group-item list-group-item-action py-2 ripple"><i
             class="fa-solid fa-plus me-3"></i><span>Add admin</span></a>
          <a href="{{route('admin.appointments')}}" class="list-group-item list-group-item-action py-2 ripple"><i
             class="fa-regular fa-clock me-3"></i><span>appointments</span></a>

      </div>
    </nav>
    <!-- Sidebar -->

    <!-- Navbar -->
    <nav id="main-navbar" class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
      <!-- Container wrapper -->
      <div class="container-fluid">
        <!-- Toggle button -->
        <button data-mdb-button-init class="navbar-toggler" type="button" data-mdb-collapse-init data-mdb-target="#sidebarMenu"
          aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fas fa-bars"></i>
        </button>

        <!-- Brand -->
        <a class="navbar-brand" href="{{route('home')}}">
          <h1 style="font-family: 'Times New Roman', Times, serif">Derma Scan Admin</h1>
        </a>


          <!-- Avatar -->
          <div class="dropdown" style="margin-right: 100px">
            <button class="btn  dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i style="color:rgb(20, 20, 181)" class="fa-solid fa-user"></i>
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{route('admin.edit',Auth::user()->id)}}">Edit profile</a></li>
              <li><a class="dropdown-item" href="{{route('password.change.admin',Auth::user()->id)}}">Change password</a></li>
              <li> <a class="dropdown-item" ><form id="logout-form" action="{{ route('logout') }}" method="POST" >
                @csrf
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout <i class="fa-solid fa-right-from-bracket"></i>
                </a>
            </form></li>
            </ul>
          </div>
        </ul>
      </div>
      <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->
  </header>
  <!--Main Navigation-->
<script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.js')}}"></script>
  <!--Main layout-->

  <!--Main layout-->
