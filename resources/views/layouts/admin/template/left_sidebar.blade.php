<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    @Include('layouts.links.logo.sidebar')

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
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->

                @foreach ($module_groups as $module_group)
                    @php
                        $counts = 1;
                    @endphp
                    @if ($module_group->status == 1)
                        @foreach ($module_group->modules as $module)
                            @if (!empty($module->permissions))
                                @if ($module->permissions['view'] == 1 && $counts == 1)
                                    <li
                                        class="nav-item @php $REQUEST_URI = '/' . Route::current(['id'])->uri(); @endphp @foreach ($module_group->modules as $module) @if (!empty($module->permissions) && !empty($module->modules_urls)) @foreach ($module->modules_urls as $modules_url) @if ($modules_url->url == $REQUEST_URI)  {{ 'menu-open' }} @endif @endforeach @endif @endforeach">
                                        <a href="#" class="nav-link">
                                            <i class="nav-icon {{ $module_group->icon }}"></i>
                                            <p>
                                                {{ $module_group->name }}
                                                <i class="right fas fa-angle-left"></i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview">
                                            @php
                                                $REQUEST_URI = '/' . Route::current(['id'])->uri();
                                            @endphp
                                            @foreach ($module_group->modules as $module)
                                                @if (!empty($module->permissions) && !empty($module->modules_urls))
                                                    @foreach ($module->modules_urls as $modules_url)
                                                        @if (
                                                            $module->id == $module->permissions['m_id'] &&
                                                                $module->permissions['view'] == 1 &&
                                                                $module->status == 1 &&
                                                                $module->type == 0 &&
                                                                $modules_url->type == 0)
                                                            <li class="nav-item menu-open">
                                                                @php
                                                                    $route_url = URL($modules_url->url);
                                                                    $url = parse_url($route_url, PHP_URL_PATH);
                                                                @endphp
                                                                <a href="{{ $url }}"
                                                                    class="nav-link @if ($modules_url->url == $REQUEST_URI) {{ 'active' }} @endif">
                                                                    <i class="{{ $module->icon }} nav-icon"></i>
                                                                    <p
                                                                        style="word-wrap: break-word;white-space: nowrap;">
                                                                        {{ $module->name }}</p>
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                    @php
                                        $counts++;
                                    @endphp
                                @endif
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
