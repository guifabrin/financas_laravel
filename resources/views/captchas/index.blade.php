<li class="nav-item dropdown" id="captchas">
    <a class="nav-link primary-color dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-image"></i>
        <span>
            {{ count($captchas) }}
        </span>
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        @foreach ($captchas as $captcha)
            {{ Form::open(['url' => '/captchas/' . $captcha->id, 'method' => 'PUT']) }}
            <a href="#" class="dropdown-item captcha">
                <img src="{{ $captcha->base64_url }}" />
            </a>
            {{ Form::close() }}
        @endforeach
    </div>
</li>
