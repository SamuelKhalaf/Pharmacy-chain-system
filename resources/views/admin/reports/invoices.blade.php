@extends('layouts.admin')
@section('title')
    Reports
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Reports</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">Reports</li>

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
                                <h3 class="card-title">All Branch Invoices</h3>
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_range">Select Date Range:</label>
                                            <input type="text" class="form-control" id="date_range" name="date_range" placeholder="Select date range">
                                        </div>
                                    </div>
                                </div>
                                <table id="example" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customer Name</th>
                                        <th>Total price</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="4" class="text-center"><b>Select branch to see it's Invoices</b></td>
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
@endsection
@section('scripts')
    <script>
        $(function () {
            $('.branch-select').on('change', function () {
                let branch_id = $(this).val();
                if (branch_id !== '') {
                    $.ajax({
                        url: "{{route('reports.get-invoices')}}",
                        type: 'GET',
                        data: { 'branch_id': branch_id },
                        success: function (response) {
                            let tbody = $('#example tbody');
                            if (!response || !response.data || response.data.length === 0) {
                                tbody.html('<tr><td colspan="4" class="text-center"><b>No invoices found for this branch</b></td></tr>');
                                return;
                            }
                            // Destroy previous DataTable instance
                            if ($.fn.DataTable.isDataTable("#example")) {
                                $('#example').DataTable().clear().destroy();
                            }
                            tbody.empty(); // Clear previous rows

                            if (response.data.length > 0) {

                                $.each(response.data, function (index, invoice) {
                                    tbody.append(`
                                        <tr>
                                            <td>${invoice.id}</td>
                                            <td>${invoice.customer_name}</td>
                                            <td>${invoice.total_price}</td>
                                            <td>${ invoice.created_at ? moment(invoice.created_at).format("ddd, DD MMM YYYY") : 'N/A' }</td>
                                            <td class="project-actions text-center">
                                                <a class="btn btn-primary btn-sm" href="/dashboard/invoice/${invoice.id}">
                                                    <i class="fas fa-folder">
                                                    </i>
                                                    View
                                                </a>
                                                <form action="/dashboard/invoice/${invoice.id}" method="POST" style="display: inline;">
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
                                tbody.append('<tr><td colspan="4" class="text-center"><b>No Invoices found for this branch</b></td></tr>');
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
                            <td colspan="4" class="text-center"><b>Select branch to see it's Invoices</b></td>
                        </tr>
                    `);
                }
            });
            // filter data ajax
            // Initialize Daterangepicker for date selection only
            $('#date_range').daterangepicker({
                autoUpdateInput: false, // Prevents pre-filling input
                locale: {
                    format: 'YYYY-MM-DD', // Date format (no time)
                    cancelLabel: 'Clear' // Adds a clear button
                }
            });
            // Trigger filtering when user clicks "Apply"
            $('#date_range').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));

                // Get selected values
                let branch_id = $('#branch_id').val();
                let date_range = $(this).val(); // Get the selected range

                if (!branch_id) {
                    alert("Please select a branch.");
                    return;
                }

                let dates = date_range.split(" to ");
                let start_date = dates[0] || null;
                let end_date = dates[1] || null;

                // Fetch filtered invoices via AJAX
                $.ajax({
                    url: "{{route('reports.get-invoices')}}",
                    type: 'GET',
                    data: {
                        'branch_id': branch_id,
                        'start_date': start_date,
                        'end_date': end_date
                    },
                    success: function (response) {
                        let tbody = $('#example tbody');
                        if (!response || !response.data || response.data.length === 0) {
                            tbody.html('<tr><td colspan="5" class="text-center"><b>No invoices found for this branch and date range</b></td></tr>');
                            return;
                        }

                        if ($.fn.DataTable.isDataTable("#example")) {
                            $('#example').DataTable().clear().destroy();
                        }

                        tbody.empty();

                        $.each(response.data, function (index, invoice) {
                            tbody.append(`
                                <tr>
                                    <td>${invoice.id}</td>
                                    <td>${invoice.customer_name}</td>
                                    <td>${invoice.total_price}</td>
                                    <td>${ invoice.created_at ? moment(invoice.created_at).format("ddd, DD MMM YYYY") : 'N/A' }</td>
                                    <td class="project-actions text-center">
                                        <a class="btn btn-primary btn-sm" href="/dashboard/invoice/${invoice.id}">
                                            <i class="fas fa-folder"></i> View
                                        </a>
                                        <form action="/dashboard/invoice/${invoice.id}" method="POST" style="display: inline;">
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
                    },
                    error: function (xhr, status, error) {
                        console.error('An error occurred:', error);
                    }
                });
            });

            // Clear input if the user clicks "Clear"
            $('#date_range').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
        });
    </script>

@endsection
