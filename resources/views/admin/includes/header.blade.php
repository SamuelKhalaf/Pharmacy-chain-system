<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        @can('super_admin')
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('home') }}" class="nav-link">Home</a>
        </li>
        @endcan
        @can('branch_admin')
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('pharmacy.index') }}" class="nav-link">Home</a>
            </li>
        @endcan
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        @can('super_admin')

            <!-- Transfer Requests Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <span id="request-count" class="badge badge-danger navbar-badge">0</span>
                    <i class="fas fa-truck"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-2">
                    <div id="latest-requests">
                        <p>Loading...</p>
                    </div>

                    <a href="{{ route('request.index') }}" class="dropdown-item dropdown-footer text-center custom-footer">
                        See All Pending Requests
                    </a>
                </div>
            </li>

            <!-- Notifications Dropdown -->
            <li class="nav-item dropdown notifications-dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-header">Loading notifications...</span>
                </div>
            </li>
        @endcan


        <!-- Fullscreen Button -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>


        <!-- Logout Button -->
        <li class="nav-item d-none d-sm-inline-block">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link btn btn-link text-truncate" style="border: none; background: none; padding: 0;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
