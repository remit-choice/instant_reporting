  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="{{ asset('assets/dist/img/AdminLTELogo.png') }}
      " alt="AdminLTELogo"
          height="60" width="60">
  </div>
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
          <li class="nav-item">
              <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
          <!-- Navbar Search -->
          <li class="nav-item">
              <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                  <i class="fas fa-search"></i>
              </a>
              <div class="navbar-search-block">
                  <form class="form-inline">
                      <div class="input-group input-group-sm">
                          <input class="form-control form-control-navbar" type="search" placeholder="Search"
                              aria-label="Search">
                          <div class="input-group-append">
                              <button class="btn btn-navbar" type="submit">
                                  <i class="fas fa-search"></i>
                              </button>
                              <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                  <i class="fas fa-times"></i>
                              </button>
                          </div>
                      </div>
                  </form>
              </div>
          </li>

          <!-- Messages Dropdown Menu -->
          <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#">
                  <i class="far fa-comments"></i>
                  <span class="badge badge-danger navbar-badge">3</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                  <a href="#" class="dropdown-item">
                      <!-- Message Start -->
                      <div class="media">
                          <img src="{{ asset('assets/dist/img/user1-128x128.jpg') }}" alt="User Avatar"
                              class="img-size-50 mr-3 img-circle">
                          <div class="media-body">
                              <h3 class="dropdown-item-title">
                                  Brad Diesel
                                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                              </h3>
                              <p class="text-sm">Call me whenever you can...</p>
                              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                          </div>
                      </div>
                      <!-- Message End -->
                  </a>
                  <div class="dropdown-divider"></div>
                  <a href="#" class="dropdown-item">
                      <!-- Message Start -->
                      <div class="media">
                          <img src="{{ asset('assets/dist/img/user8-128x128.jpg') }}" alt="User Avatar"
                              class="img-size-50 img-circle mr-3">
                          <div class="media-body">
                              <h3 class="dropdown-item-title">
                                  John Pierce
                                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                              </h3>
                              <p class="text-sm">I got your message bro</p>
                              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                          </div>
                      </div>
                      <!-- Message End -->
                  </a>
                  <div class="dropdown-divider"></div>
                  <a href="#" class="dropdown-item">
                      <!-- Message Start -->
                      <div class="media">
                          <img src="{{ asset('assets/dist/img/user3-128x128.jpg') }}" alt="User Avatar"
                              class="img-size-50 img-circle mr-3">
                          <div class="media-body">
                              <h3 class="dropdown-item-title">
                                  Nora Silvester
                                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                              </h3>
                              <p class="text-sm">The subject goes here</p>
                              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                          </div>
                      </div>
                      <!-- Message End -->
                  </a>
                  <div class="dropdown-divider"></div>
                  <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
              </div>
          </li>
          <!-- Notifications Dropdown Menu -->
          <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#">
                  <i class="far fa-bell"></i>
                  <span class="badge badge-warning navbar-badge">15</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                  <span class="dropdown-item dropdown-header">15 Notifications</span>
                  <div class="dropdown-divider"></div>
                  <a href="#" class="dropdown-item">
                      <i class="fas fa-envelope mr-2"></i> 4 new messages
                      <span class="float-right text-muted text-sm">3 mins</span>
                  </a>
                  <div class="dropdown-divider"></div>
                  <a href="#" class="dropdown-item">
                      <i class="fas fa-users mr-2"></i> 8 friend requests
                      <span class="float-right text-muted text-sm">12 hours</span>
                  </a>
                  <div class="dropdown-divider"></div>
                  <a href="#" class="dropdown-item">
                      <i class="fas fa-file mr-2"></i> 3 new reports
                      <span class="float-right text-muted text-sm">2 days</span>
                  </a>
                  <div class="dropdown-divider"></div>
                  <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
              </div>
          </li>
          <li class="nav-item">
              <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                  <i class="fas fa-expand-arrows-alt"></i>
              </a>
          </li>
          <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#">
                  <i class="fas fa-sign-out-alt"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                  <form action="/logout" method="post">
                      @csrf
                      <button type="submit" class="dropdown-item">
                          <i class="fas fa-sign-out-alt mr-2"></i> Logout
                      </button>

                  </form>

              </div>
          </li>
      </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="" class="brand-link">
          <img src="{{ asset('assets/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
              class="brand-image img-circle elevation-3" style="opacity: .8">
          <span class="brand-text font-weight-light">@Include('layouts.links.admin.title')</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
          <!-- Sidebar user panel (optional) -->
          <div class="user-panel mt-3 pb-3 mb-3 d-flex">
              <div class="image">
                  <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                      alt="User Image">
              </div>
              <div class="info">
                  <a href="#" class="d-block">{{ Session::get('full_name') }}</a>
              </div>
          </div>


          <!-- SidebarSearch Form -->
          <div class="form-inline">
              <div class="input-group" data-widget="sidebar-search">
                  <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                      aria-label="Search">
                  <div class="input-group-append">
                      <button class="btn btn-sidebar">
                          <i class="fas fa-search fa-fw"></i>
                      </button>
                  </div>
              </div>
          </div>

          <!-- Sidebar Menu -->
          <nav class="mt-2">
              <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview"
                  role="menu" data-accordion="false">
                  <!-- Add icons to the links using the .nav-icon class
       with font-awesome or any other icon font library -->
                  @foreach ($module_groups as $module_group)
                      @if ($module_group->status == 1)
                          <li class="nav-item">
                              <a href="#" class="nav-link">
                                  <i class="nav-icon {{ $module_group->icon }}"></i>
                                  <p>
                                      {{ $module_group->name }}
                                      <i class="right fas fa-angle-left"></i>
                                  </p>
                              </a>
                              <ul class="nav nav-treeview">
                                  @foreach ($module_group->modules as $module)
                                      @if (!empty($module->permissions) && !empty($module->modules_urls))
                                          @foreach ($module->modules_urls as $modules_url)
                                              @if ($module->id == $module->permissions['m_id'] &&
                                                  $module->permissions['view'] == 1 &&
                                                  $module->status == 1 &&
                                                  $module->type == 0 &&
                                                  $modules_url->type == 0)
                                                  <li class="nav-item">
                                                      @php
                                                          $route_url = URL($modules_url->url);
                                                      @endphp
                                                      <a href="{{ $route_url }}" class="nav-link">
                                                          <i class="{{ $module->icon }} nav-icon"></i>
                                                          <p>{{ $module->name }}</p>
                                                      </a>
                                                  </li>
                                              @endif
                                          @endforeach
                                      @endif
                                  @endforeach
                              </ul>
                          </li>
                      @endif
                  @endforeach
              </ul>
          </nav>
          <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
  </aside>
  @yield('content')

  <!-- /.content-wrapper -->
  <footer class="main-footer">
      <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">Web Training</a>.</strong>
      All rights reserved.
      <div class="float-right d-none d-sm-inline-block">
          <b>Version</b> 3.1.0
      </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->
