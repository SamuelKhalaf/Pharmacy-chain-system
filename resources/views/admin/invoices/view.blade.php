@extends('layouts.admin')
@section('title')
    Invoices
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Invoice Items</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">Invoice Items</li>

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
                        @if($invoiceItems->isNotEmpty())
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">View Invoice Items</h3>
                            </div>
                            <!-- /.card-header -->
                                <div class="card-body">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Price For One Piece</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($invoiceItems as $item)
                                            <tr>
                                                <td>{{$item->product_name}}</td>
                                                <td>{{$item->quantity}}</td>
                                                <td>{{$item->price}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            <!-- /.card-body -->
                        </div>
                        @else
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">No Invoice Items Available</h3>
                                </div>
                                <div class="card-body">
                                    <p>There are no Items to display.</p>
                                </div>
                            </div>
                        @endif
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
