<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Admin Dashboard</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{route('home')}}" class="nav-link {{setActive('home')}}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item {{setMenuOpen('dashboard/admin')}}">
                    <a href="" class="nav-link {{setActive('admin.create')}} {{setActive('admin.index')}}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Admins
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('admin.index')}}" class="nav-link {{setActive('admin.index')}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>All Admins</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('admin.create')}}" class="nav-link {{setActive('admin.create')}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>New Admin</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{setMenuOpen('dashboard/category')}}">
                    <a href="" class="nav-link {{setActive('category.create')}} {{setActive('category.index')}}">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            Categories
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('category.index')}}" class="nav-link {{setActive('category.index')}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>All Categories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('category.create')}}" class="nav-link {{setActive('category.create')}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>New category</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{setMenuOpen('dashboard/product')}}">
                    <a href="" class="nav-link {{setActive('product.create')}} {{setActive('product.index')}}">
                        <i class="nav-icon fas fa-box-open"></i>
                        <p>
                            Products
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('product.index')}}" class="nav-link {{setActive('product.index')}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>All Products</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('product.create')}}" class="nav-link {{setActive('product.create')}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>New product</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Simple Link
                            <span class="right badge badge-danger">New</span>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
