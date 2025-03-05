@extends('layouts.admin')
@section('title')
    Pharmacy
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Pharmacy</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('pharmacy.index')}}">Home</a></li>
                            <li class="breadcrumb-item active">Pharmacy</li>
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
                                <h3 class="card-title">All Pharmacy Products</h3>
                            </div>
                            @include('admin.includes.alerts.success')
                            @include('admin.includes.alerts.errors')
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>product name</th>
                                            <th>product quantity</th>
                                            <th>product price</th>
                                            <th>Critical Level</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($inventoryProducts->isNotEmpty())
                                            @foreach($inventoryProducts as $inventoryProduct)
                                                <tr>
                                                    <td>{{$inventoryProduct->product_name}}</td>
                                                    <td>{{$inventoryProduct->quantity}}</td>
                                                    <td>{{$inventoryProduct->price}}</td>
                                                    <td>{{$inventoryProduct->critical_level ?? 'N/A' }}</td>
                                                    <td class="project-actions text-center">
                                                        <a class="btn btn-primary btn-sm" href="{{route('pharmacy.show',[$inventoryProduct->branch_id,$inventoryProduct->product_id])}}">
                                                            <i class="fas fa-folder"></i> View
                                                        </a>
                                                        <a class="btn btn-info btn-sm" href="{{route('pharmacy.edit',[$inventoryProduct->branch_id,$inventoryProduct->product_id])}}">
                                                            <i class="fas fa-pencil-alt"></i> Edit
                                                        </a>
                                                        <form action="{{route('pharmacy.destroy',[$inventoryProduct->branch_id,$inventoryProduct->product_id])}}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5"> There is Not Products In Pharmacy</td>
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
