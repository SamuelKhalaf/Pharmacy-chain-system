@extends('layouts.admin')
@section('title')
    Edit Inventory Product
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Inventory Product</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('inventory.index')}}">Inventories</a></li>
                            <li class="breadcrumb-item active">Edit Inventory Product</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Inventory Product</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{route('inventory.update',[$inventory->branch_id,$inventory->product_id])}}" method="post" autocomplete="off">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <p>Edit Inventory
                                <span class="text-success"><b>{{$inventory->branch_name}}</b></span>
                                Product
                                <span class="text-success"><b>{{$inventory->product_name}}</b></span>
                            </p>
                            <div class="form-group">
                                <label for="quantity">Product Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="{{$inventory->quantity ?? old('quantity')}}"
                                       placeholder="Enter Product Quantity" min="1">
                                @error('quantity')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="price">Product Price</label>
                                <input type="number" class="form-control" id="price" name="price" value="{{$inventory->price ?? old('price')}}"
                                       placeholder="Enter Price" min="1" step="0.01">
                                @error('price')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div><!-- /.container-fluid -->
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
