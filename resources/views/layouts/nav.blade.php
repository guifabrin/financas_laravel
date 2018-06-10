<h1 style="display: none;">{{ config('app.name', 'Laravel') }}</h1>
<nav class="navbar navbar-expand-lg">
  <a class="navbar-brand title" href="{{ url('/') }}">
    {{ config('app.name', 'Laravel') }}
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
    <ul class="navbar-nav">
      @if (Auth::guest())
        <li class="nav-item"><a href="{{ route('login') }}" class="nav-link primary-color">{{__('login.login')}}</a></li>
        <li class="nav-item"><a href="{{ route('register') }}" class="nav-link primary-color">{{__('login.register')}}</a></li>
      @else
        <li class="nav-item dropdown">
          <a class="nav-link primary-color dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{ Auth::user()->name }}
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a href="/accounts" class="dropdown-item">
              {{__('accounts.title')}}
            </a>
            <a href="{{ route('logout') }}" class="dropdown-item"
               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
              {{__('login.logout')}}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                  style="display: none;">
                {{ csrf_field() }}
            </form>
          </div>
        </li>
      @endif
    </ul>
  </div>
</nav>