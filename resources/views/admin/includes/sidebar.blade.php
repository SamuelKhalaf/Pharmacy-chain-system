@php use App\Enums\AdminRole;use Illuminate\Support\Facades\Auth; @endphp
    <!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
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
                <a href="#" class="d-block">{{Auth::user()->name ?? 'N/A'}}</a>
                <small class="text-muted">{{ AdminRole::getRoleName(auth()->user()->role_id) ?? 'No Role' }}</small>
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
                                <i class="fas fa-list-alt nav-icon"></i>
                                <p>All Admins</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('admin.create')}}" class="nav-link {{setActive('admin.create')}}">
                                <i class="fas fa-plus-square nav-icon"></i>
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
                                <i class="fas fa-list-alt nav-icon"></i>
                                <p>All Categories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('category.create')}}" class="nav-link {{setActive('category.create')}}">
                                <i class="fas fa-plus-square nav-icon"></i>
                                <p>New category</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{setMenuOpen('dashboard/product')}}">
                    <a href="" class="nav-link {{setActive('product.create')}} {{setActive('product.index')}}">
                        <i class="nav-icon fas fa-briefcase"></i>
                        <p>
                            Products
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('product.index')}}" class="nav-link {{setActive('product.index')}}">
                                <i class="fas fa-list-alt nav-icon"></i>
                                <p>All Products</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('product.create')}}" class="nav-link {{setActive('product.create')}}">
                                <i class="fas fa-plus-square nav-icon"></i>
                                <p>New product</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{setMenuOpen('dashboard/branch')}}">
                    <a href="" class="nav-link {{setActive('branch.create')}} {{setActive('branch.index')}}">
                        <i class="nav-icon fas fa-map-marked-alt"></i>
                        <p>
                            Branches
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('branch.index')}}" class="nav-link {{setActive('branch.index')}}">
                                <i class="fas fa-list-alt nav-icon"></i>
                                <p>All Branches</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('branch.create')}}" class="nav-link {{setActive('branch.create')}}">
                                <i class="fas fa-plus-square nav-icon"></i>
                                <p>New branch</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{setMenuOpen('dashboard/inventory')}}">
                    <a href="" class="nav-link {{setActive('inventory.create')}} {{setActive('inventory.index')}}">
                        <i class="nav-icon fas fa-store"></i>
                        <p>
                            Inventories
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('inventory.index')}}" class="nav-link {{setActive('inventory.index')}}">
                                <i class="fas fa-list-alt nav-icon"></i>
                                <p>All Inventory Product/s</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('inventory.create')}}" class="nav-link {{setActive('inventory.create')}}">
                                <i class="fas fa-plus-square nav-icon"></i>
                                <p>New inventory Product/s</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{setMenuOpen('dashboard/reports')}}">
                    <a href="" class="nav-link {{setActive('reports.branches-invoices')}} {{setActive('reports.products-count')}}">
                        <i class="nav-icon fas fa-file-alt "></i>
                        <p>
                            Reports
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('reports.branches-invoices')}}" class="nav-link {{setActive('reports.branches-invoices')}}">
                                <i class="fas fa-shopping-cart nav-icon"></i>
                                <p>Branch Invoices</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('reports.products-count')}}" class="nav-link {{setActive('reports.products-count')}}">
                                <i class="fas fa-calculator nav-icon"></i>
                                <p>Sold Products Count</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- Super Admin transfer link --}}
                <li class="nav-item">
                    <a href="{{route('transfer.show-transfer-form')}}"
                       class="nav-link {{setActive('transfer.show-transfer-form')}}">
                        <i class="nav-icon fas fa-exchange-alt"></i>
                        <p>
                            Transfer Products
                        </p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{route('pharmacy.index')}}" class="nav-link {{setActive('pharmacy.index')}}">
                        <i class="nav-icon fas fa-clinic-medical"></i>
                        <p>
                            Pharmacy
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ setMenuOpen('dashboard/invoice') }}">
                    <a href="#"
                       class="nav-link {{ setActive('invoice.create') }} {{ setActive('invoice.index') }} {{ setActive('invoice.show') }} {{ setActive('invoice.edit') }}">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>
                            Invoices
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('invoice.index') }}" class="nav-link {{ setActive('invoice.index') }}">
                                <i class="fas fa-list-alt nav-icon"></i>
                                <p>All Invoices</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('invoice.create') }}" class="nav-link {{ setActive('invoice.create') }}">
                                <i class="fas fa-plus-square nav-icon"></i>
                                <p>New Invoice</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- Branch Admin transfer-request link --}}
                <li class="nav-item">
                    <a href="{{route('request.show-request-form')}}"
                       class="nav-link {{setActive('request.show-request-form')}}">
                        <i class="nav-icon fas fa-handshake"></i>
                        <p>
                            Request Products
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
