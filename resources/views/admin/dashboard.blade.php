@extends('layouts.admin')
@section('title')
    Dashboard
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">Home</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        @include('admin.includes.alerts.success')
        @include('admin.includes.alerts.errors')
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>150</h3>

                                <p>New Orders</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>53<sup style="font-size: 20px">%</sup></h3>

                                <p>Bounce Rate</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>44</h3>

                                <p>User Registrations</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>65</h3>

                                <p>Unique Visitors</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <!-- /.row -->
                <!-- Main row -->
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header border-0">
                                <div class="d-flex justify-content-between">
                                    <h3 class="card-title">Sales</h3>
                                    <a>View Report</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="input-group mb-3">
                                    <input type="text" id="sales-date" class="form-control" placeholder="Select Month & Year">
                                    <button id="fetch-sales" class="btn btn-primary">Get Sales Data</button>
                                </div>
                                <div class="position-relative mb-4">
                                    <canvas id="sales-chart" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="card-title">Most Sold Products</h3>
                                <div class="card-tools">
                                    <select id="products-year" class="form-control form-control-sm">
                                        @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <button id="fetch-products" class="btn btn-sm btn-primary">Refresh</button>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-striped table-valign-middle">
                                    <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Sales</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr><td colspan="4" class="text-center">Loading...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row (main row) -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#sales-date').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoApply: true,
                locale: {
                    format: 'YYYY-MM'
                }
            });

            $('#fetch-sales').on('click', function () {
                let selectedDate = $('#sales-date').val();
                let [year, month] = selectedDate.split('-');

                $.ajax({
                    url: "{{ route('charts.sales-count') }}",
                    type: "GET",
                    dataType: "json",
                    data: { month: month, year: year },
                    success: function (data) {
                        console.log("Fetched Data:", data);
                        updateChart(data);
                    },
                    error: function (xhr) {
                        console.error("Error fetching sales data:", xhr.responseText);
                    }
                });
            });

            function updateChart(data) {
                let ctx = $("#sales-chart")[0].getContext("2d");

                if (window.salesChart) {
                    window.salesChart.destroy();
                }

                if (!Array.isArray(data) || data.length === 0) {
                    console.error("Invalid data format or empty response.");
                    return;
                }

                let branches = data.map(item => item.branch);
                let sales = data.map(item => item.sales);

                window.salesChart = new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: branches,
                        datasets: [{
                            label: "Sales Count",
                            backgroundColor: "rgba(54, 162, 235, 0.6)",
                            borderColor: "rgba(54, 162, 235, 1)",
                            borderWidth: 1,
                            data: sales
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                min: 0
                            }
                        }
                    }
                });
            }
            $.ajax({
                url: "{{ route('charts.sales-count') }}",
                method: "GET",
                dataType: "json",
                success: function (data) {
                    console.log("Initial Data:", data);
                    updateChart(data);
                },
                error: function (xhr) {
                    console.error("Error fetching initial sales data:", xhr.responseText);
                }
            });

            // the ajax for most sold products table
            function fetchTopProducts(year = new Date().getFullYear()) {
                $.ajax({
                    url: "{{ route('dashboard.top-products') }}",
                    type: "GET",
                    data: { year: year },
                    dataType: "json",
                    success: function (data) {
                        updateProductTable(data);
                    },
                    error: function (xhr) {
                        console.error("Error fetching top products:", xhr.responseText);
                    }
                });
            }

            function updateProductTable(products) {
                let tableBody = $(".table-valign-middle tbody");
                tableBody.empty();

                if (products.length === 0) {
                    tableBody.append('<tr><td colspan="4" class="text-center">No sales data available</td></tr>');
                    return;
                }

                products.forEach(product => {
                    let productRow = `
                    <tr>
                        <td>
                            <img src="dist/img/default-150x150.png" class="img-circle img-size-32 mr-2">
                            ${product.name}
                        </td>
                        <td>$${product.price} USD</td>
                        <td>
                            <small class="text-success mr-1">
                                <i class="fas fa-arrow-up"></i>
                                ${product.total_sold} Sold
                            </small>
                        </td>
                    </tr>
                `;
                    tableBody.append(productRow);
                });
            }

            fetchTopProducts();

            $("#fetch-products").on("click", function () {
                let selectedYear = $("#products-year").val() || new Date().getFullYear();
                fetchTopProducts(selectedYear);
            });
        });
    </script>

@endsection
