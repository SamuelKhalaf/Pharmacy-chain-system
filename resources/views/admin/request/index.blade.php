@extends('layouts.admin')
@section('title')
    Requests
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Requests</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">Requests</li>

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
                                <h3 class="card-title">All Pending Requests</h3>
                            </div>
                            @include('admin.includes.alerts.success')
                            @include('admin.includes.alerts.errors')
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="requests-table table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>From Branch</th>
                                        <th>To Branch</th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($requests->isNotEmpty())
                                        @foreach($requests as $request)
                                            <tr id="request_{{$request->id}}">
                                                <td>{{$request->from_branch_name}}</td>
                                                <td>{{$request->to_branch_name}}</td>
                                                <td>{{$request->product_name}}</td>
                                                <td>{{$request->quantity}}</td>
                                                <td>{{$request->status}}</td>
                                                <td class="project-actions text-right">
                                                    <button class="btn btn-success btn-sm accept-request" data-id="{{ $request->id }}">
                                                        <i class="fas fa-check"></i> Accept
                                                    </button>
                                                    <button class="btn btn-danger btn-sm cancel-request" data-id="{{ $request->id }}">
                                                        <i class="fas fa-times"></i> Cancel
                                                    </button>
                                                </td>

                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6">
                                                <div class="card border-0 text-center p-3">
                                                    <div class="card-body">
                                                        <h6 class="text-muted mb-1">
                                                            <i class="fas fa-info-circle"></i> No pending requests
                                                        </h6>
                                                        <p class="text-muted small">All transfer requests have been processed. Please check again later.</p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
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
@endsection
