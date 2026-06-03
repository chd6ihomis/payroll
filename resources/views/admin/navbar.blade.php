<!-- Navbar -->
<nav class="main-header navbar navbar-expand text-sm navbar-light navbar-white shadow-sm">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <!-- <li class="nav-item d-none d-sm-inline-block">
        <a href="index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li> -->
  </ul>

  <!-- SEARCH FORM -->
  <!-- <form class="form-inline ml-3">
    <div class="input-group input-group-sm">
      <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
      <div class="input-group-append">
        <button class="btn btn-navbar" type="submit">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </div>
  </form> -->

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Authentication Links -->
    @guest
    <li class="nav-item">
      <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
    </li>
    @if (Route::has('register'))
    <li class="nav-item">
      <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
    </li>
    @endif
    @else
    <li class="nav-item text-wrap"><a class="nav-link" href="#">{{ \Carbon\Carbon::now()->toDayDateTimeString() }}</a></li>

    @if(auth()->user()->role == '0')
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-danger navbar-badge">{{ $notifications->count() }}</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">{{ $notifications->count() }} Notifications</span>
        @forelse($notifications as $notification)
        <div class="dropdown-divider"></div>
        <a href="{{ route('salaries.show', $notification->payroll_id ) }}" class="dropdown-item">
          <div class="media">
            <img src="/image/userlogo.png" alt="User Avatar" class="img-size-50 img-circle mr-3">
            <div class="media-body">
              <h3 class="dropdown-item-title">
                {{ Str::limit($notification->sender, 20) }}
                <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
              </h3>
              <p class="text-sm" style="text-overflow: ellipsis;">{{ Str::limit($notification->comment, 15) }}</p>
              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }} </p>
            </div>
          </div>
        </a>
        @empty
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fab fa-facebook-messenger mr-2"></i> You have 0 notification!
        </a>
        @endforelse
    </li>
    @endif


    <li class="nav-item dropdown">
      <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
        {{ Auth::user()->name }} <span class="caret"></span>
      </a>

      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        <a class="dropdown-item" href="{{ route('changepassword', auth()->user()->id) }}">
          {{ __('Change Password') }}
        </a>
        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
          {{ __('Logout') }}
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </div>
    </li>
	<!-- <li class="nav-item">
		<a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
			<i class="fas fa-comments"></i>
			<span class="badge badge-danger navbar-badge">{{ $chats = \DB::table('chats')->where('to', auth()->user()->id)->get()->count() }}</span>
		</a>
	</li> -->
    @endguest
  </ul>
</nav>
<!-- /.navbar -->