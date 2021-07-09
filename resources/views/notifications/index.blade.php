<li class="nav-item dropdown" id="notifications">
    <a class="nav-link primary-color dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell"></i>
        <span>
            {{ $notificationsCount }}
        </span>
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        @foreach ($notifications as $notification)
            {{ Form::open(['url' => '/notifications/' . $notification->notification_id, 'method' => 'PUT']) }}
            <a href="#" class="dropdown-item notification {{ $notification->seen ? '' : 'active' }}">
                {{ __('common.imported') }} {{ $notification->id }}
                <small style="display: block;">
                    {{ $notification->date }} {{ $notification->description }}
                </small>
                <small>
                    {{ $notification->account_id }} / {{ $notification->account_description }}
                </small>
            </a>
            {{ Form::close() }}
        @endforeach
    </div>
</li>
