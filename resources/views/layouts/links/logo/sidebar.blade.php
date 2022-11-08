    @if (config('app.env')=='production')
        <a href="" class="brand-link">
          <img src="{{ secure_asset('assets/dist/img/Admin_logo_sidebar.png') }}" alt="AdminLTE Logo"
              class="brand-image img-circle elevation-3" style="opacity: .8">
          <span class="brand-text font-weight-light">@Include('layouts.links.admin.title')</span>
        </a>
    @else
        <a href="" class="brand-link">
          <img src="{{ asset('assets/dist/img/Admin_logo_sidebar.png') }}" alt="AdminLTE Logo"
              class="brand-image img-circle elevation-3" style="opacity: .8">
          <span class="brand-text font-weight-light">@Include('layouts.links.admin.title')</span>
        </a>
    @endif