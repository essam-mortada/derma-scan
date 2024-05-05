<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">admin dashboard</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{route('home')}}">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('admin.posts')}}">posts</a>
          </li>
          <li class="nav-item">
            <a class="nav-link " aria-current="page" href="{{route('admin.users')}}">users</a>
          </li>
          <li class="nav-item">
            <a class="nav-link " aria-current="page" href="{{route('admin.doctor-requests')}}">doctor requests</a>
          </li>
          <li class="nav-item">
            <a class="nav-link " aria-current="page" href="{{route('admin.edit',Auth::user()->id)}}">edit profile</a>
          </li>
         
        </ul>
        
      </div>
    </div>
  </nav>

  @yield('content')
</body>
</html>