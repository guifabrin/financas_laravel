<h1 style="display: none;">{{ config('app.name', 'Laravel') }}</h1>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary container-fluid">
    <a class="navbar-brand title" href="{{ url('/') }}">
        <img src="{{ asset('images/icon.png') }}" style="width: 24px;" /> {{ config('app.name', 'Laravel') }}
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
        <ul class="navbar-nav">
            @if (Auth::guest())
                <li class="nav-item"><a href="{{ route('login') }}"
                        class="nav-link primary-color">{{ __('login.login') }}</a></li>
                <li class="nav-item"><a href="{{ route('register') }}"
                        class="nav-link primary-color">{{ __('login.register') }}</a></li>
            @else
                @if (isset($notifications))
                    @include('notifications.index')
                @endif
                @if (isset($captchas))
                    @include('captchas.index')
                @endif
                <li class="nav-item dropdown">
                    <a class="nav-link primary-color dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span>
                            {{ $theme }}
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        @foreach ($themes as $ntheme)
                            <a href="{{ request()->fullUrlWithQuery(['theme' => $ntheme]) }}"
                                class="dropdown-item theme {{ $ntheme == $theme ? 'active' : '' }}">
                                {{ $ntheme }}
                            </a>
                        @endforeach
                    </div>
                </li>


                <li class="nav-item">
                    <a href="{{ request()->fullUrlWithQuery(['fontSize' => $fontSize * 1.1]) }}"
                        class="nav-link primary-color {{ $fontSize > 1 ? 'active' : '' }}">A+</a>
                </li>

                <li class="nav-item">
                    <a href="{{ request()->fullUrlWithQuery(['fontSize' => $fontSize * 0.9]) }}"
                        class="nav-link primary-color {{ $fontSize < 1 ? 'active' : '' }}">A-</a>
                </li>

                <li class="nav-item">
                    <a href="{{ request()->fullUrlWithQuery(['compactMode' => $compactMode ? false : true]) }}"
                        class="nav-link primary-color {{ $compactMode ? 'active' : '' }}">
                        <i class="fa fa-compact-disc"></i>
                    </a>
                </li>

                <li class="nav-item"><a href="{{ Request::url() }}" class="nav-link primary-color">
                        <i class="fas fa-sync"></i>
                    </a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link primary-color dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a href="{{ url('accounts') }}" class="dropdown-item">
                            {{ __('accounts.title') }}
                        </a>
                        <a href="{{ url('transactions') }}" class="dropdown-item">
                            {{ __('transactions.title') }}
                        </a>
                        @if (Auth::user()->hasRole('admin'))
                            <div class="dropdown-divider"></div>
                            <a href="{{ url('translations') }}" class="dropdown-item">
                                {{ __('translations.title') }}
                            </a>
                            <a href="{{ url('users') }}" class="dropdown-item">
                                {{ __('users.title') }}
                            </a>
                        @endif
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" class="dropdown-item"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            {{ __('login.logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            @endif
        </ul>
    </div>
</nav>
