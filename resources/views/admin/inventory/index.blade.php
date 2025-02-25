@extends('layouts.admin')
@section('title')
    Inventories
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Inventories</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">Inventories</li>

                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">All Inventories</h3>
                            </div>
                            @include('admin.includes.alerts.success')
                            @include('admin.includes.alerts.errors')
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="branch_id">Select Branch:</label>
                                            <select class="form-control branch-select" name="branch_id" id="branch_id" required>
                                                <option value="" selected disabled >-- Select Branch --</option>
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <table id="example" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>product name</th>
                                        <th>product quantity</th>
                                        <th>product price</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="4" class="text-center"><b>Select branch to see it's Products</b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
        <div class="p-3">
            <h5>Title</h5>
            <p>Sidebar content</p>
        </div>
    </aside>
    <!-- /.control-sidebar -->
@endsection
@section('scripts')
    <script>
        $(function () {
            $('.branch-select').on('change', function () {
                let branch_id = $(this).val();
                if (branch_id !== '') {
                    $.ajax({
                        url: "{{route('allInventoryProducts.ajax')}}",
                        type: 'GET',
                        data: { 'branch_id': branch_id },
                        success: function (response) {
                            let tbody = $('#example tbody');
                            if (!response || !response.data || response.data.length === 0) {
                                $('#example tbody').html('<tr><td colspan="4" class="text-center"><b>No products found for this branch</b></td></tr>');
                                return;
                            }
                            // Destroy previous DataTable instance
                            if ($.fn.DataTable.isDataTable("#example")) {
                                $('#example').DataTable().clear().destroy();
                            }
                            tbody.empty(); // Clear previous rows

                            if (response.data.length > 0) {

                                $.each(response.data, function (index, inventory) {
                                    tbody.append(`
                                        <tr>
                                            <td>${inventory.product_name}</td>
                                            <td>${inventory.quantity}</td>
                                            <td>${inventory.price}</td>
                                            <td class="project-actions text-center">
                                                <a class="btn btn-primary btn-sm" href="/dashboard/inventory/${inventory.branch_id}/${inventory.product_id}">
                                                    <i class="fas fa-folder"></i> View
                                                </a>
                                                <a class="btn btn-info btn-sm" href="/dashboard/inventory/${inventory.branch_id}/${inventory.product_id}/edit">
                                                    <i class="fas fa-pencil-alt"></i> Edit
                                                </a>
                                                <form action="/dashboard/inventory/${inventory.branch_id}/${inventory.product_id}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    `);
                                });
                            } else {
                                tbody.append('<tr><td colspan="4" class="text-center"><b>No products found for this branch</b></td></tr>');
                            }

                            if ($('#example tbody tr').length > 0) {
                                $('#example').DataTable({
                                    "deferRender": true,
                                    "paging": true,
                                    "lengthChange": true,
                                    "searching": true,
                                    "ordering": true,
                                    "info": true,
                                    "autoWidth": false,
                                    "responsive": true,
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('An error occurred:', error);
                        }
                    });
                }else{
                    $('#example tbody').html(`
                        <tr>
                            <td colspan="4" class="text-center"><b>Select branch to see it's Products</b></td>
                        </tr>
                    `);
                }
            });
        });
    </script>

@endsection
