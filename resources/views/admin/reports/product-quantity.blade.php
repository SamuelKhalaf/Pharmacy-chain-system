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
                                <h3 class="card-title">Sold Products Quantity</h3>
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
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="product_id">Select Product:</label>
                                            <select class="form-control" name="product_id" id="product_id">
                                                <option value="" selected>-- Select Product --</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="date_range">Select Date Range:</label>
                                            <input type="text" class="form-control" id="date_range" name="date_range" placeholder="Select date range">
                                        </div>
                                    </div>
                                </div>
                                <table id="example" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Total Quantity Sold</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="2" class="text-center"><b>Select branch and Product to see the total sold quantity</b></td>
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
            // Fetch products when branch is selected
            $('#branch_id').on('change', function () {
                let branch_id = $(this).val();
                $('#product_id').html('<option value="" selected disabled>-- Loading Products --</option>');

                if (branch_id !== '') {
                    $.ajax({
                        url: "{{ route('reports.get-products-by-branch') }}",
                        type: 'GET',
                        data: { branch_id: branch_id },
                        success: function (response) {
                            let productSelect = $('#product_id');
                            productSelect.html('<option value="" selected disabled>-- Select Product --</option>');

                            if (response.data.length > 0) {
                                $.each(response.data, function (index, product) {
                                    productSelect.append(`<option value="${product.product_id}">${product.product_name}</option>`);
                                });
                            } else {
                                productSelect.append('<option value="" disabled>No products available</option>');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Error fetching products:', error);
                        }
                    });
                }
            });

            // Fetch sold product quantity when a product is selected
            $('#product_id').on('change', function () {
                fetchSoldProductQuantity();
            });

            // Handle date range selection
            $('#date_range').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Clear'
                }
            });

            $('#date_range').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
                fetchSoldProductQuantity();
            });

            $('#date_range').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
                fetchSoldProductQuantity();
            });

            function fetchSoldProductQuantity() {
                let branch_id = $('#branch_id').val();
                let product_id = $('#product_id').val();
                let date_range = $('#date_range').val();

                if (!branch_id || !product_id) {
                    alert("Please select branch and product");
                    return;
                }

                let start_date = null, end_date = null;
                if (date_range) {
                    let dates = date_range.split(" to ");
                    start_date = dates[0];
                    end_date = dates[1];
                }

                $.ajax({
                    url: "{{ route('reports.get-sold-product-quantity') }}", // API to get sold quantity
                    type: 'GET',
                    data: {
                        branch_id: branch_id,
                        product_id: product_id,
                        start_date: start_date,
                        end_date: end_date
                    },
                    success: function (response) {
                        let tbody = $('#example tbody');
                        tbody.empty();

                        if (!response.data || response.data.length === 0) {
                            tbody.append('<tr><td colspan="2" class="text-center"><b>No sales data available</b></td></tr>');
                            return;
                        }

                        $.each(response.data, function (index, item) {
                            tbody.append(`
                                <tr>
                                    <td>${item.name}</td>
                                    <td>${item.total_quantity_sold}</td>
                                </tr>
                            `);
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching sales data:', error);
                    }
                });
            }
        });

    </script>

@endsection
