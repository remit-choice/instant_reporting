  @if (config('app.env')=='production')
    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="{{ secure_asset('assets/dist/img/Admin_logo.png') }}
        " alt="AdminLTELogo"
            height="60" width="60">
    </div>
   @else
      <!-- Preloader -->
      <div class="preloader flex-column justify-content-center align-items-center">
          <img class="animation__shake" src="{{ asset('assets/dist/img/Admin_logo.png') }}
          " alt="AdminLTELogo"
              height="60" width="60">
      </div>
   @endif