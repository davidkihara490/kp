<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>

        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ url('/') }}" class="nav-link">Home</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <!-- User Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle"
               href="#"
               id="userDropdown"
               data-toggle="dropdown"
               aria-haspopup="true"
               aria-expanded="false">

                <i class="far fa-user-circle mr-1"></i>

                <span class="d-none d-sm-inline">
                    {{ auth()->user()->name ?? 'Account' }}
                </span>
            </a>

            <div class="dropdown-menu dropdown-menu-right"
                 aria-labelledby="userDropdown">

                <!-- User Information -->
                <div class="dropdown-item-text">
                    <strong>
                        {{ auth()->user()->name ?? 'User' }}
                    </strong>

                    @if(auth()->user()?->email)
                        <small class="d-block text-muted">
                            {{ auth()->user()->email }}
                        </small>
                    @endif
                </div>

                <div class="dropdown-divider"></div>

                <!-- Profile -->
                <!-- <a href="#"
                   class="dropdown-item">
                    <i class="fas fa-user mr-2"></i>
                    My Profile
                </a> -->

                <div class="dropdown-divider"></div>

                <!-- Logout -->
                <form method="POST"
                      action="{{ route('admin.logout') }}"
                      id="logout-form">
                    @csrf

                    <button type="submit"
                            class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Logout
                    </button>
                </form>

            </div>
        </li>

    </ul>
</nav>