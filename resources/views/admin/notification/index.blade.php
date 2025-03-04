@extends('layouts.admin')
@section('title')
    Notifications
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Notifications</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">Notifications</li>
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
                                <h3 class="card-title">All Notifications</h3>
                            </div>
                            @include('admin.includes.alerts.success')
                            @include('admin.includes.alerts.errors')
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Message</th>
                                            <th>Branch</th>
                                            <th>Product</th>
                                            <th>Product Quantity</th>
                                            <th>Critical Level</th>
                                            <th>Is Read</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($notifications->isNotEmpty())
                                            @foreach($notifications as $notification)
                                                <tr>
                                                    <td>{{$notification->data['text']}}</td>
                                                    <td>{{$notification->data['branch_name']}}</td>
                                                    <td>{{$notification->data['product_name']}}</td>
                                                    <td>{{$notification->data['product_quantity']}}</td>
                                                    <td>{{$notification->data['critical_level']}}</td>
                                                    <td>{{ $notification->is_read ? 'true' : 'false' }}</td>
                                                    <td class="project-actions text-center">
                                                        <form action="{{route('notification.markAsRead',[$notification->id])}}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                <i class="fas fa-check"></i> Mark As Read
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5"> There is Not Notifications</td>
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
    <!-- /.content-wrapper -->>
@endsection
