@extends('layouts.admin')
@section('title')
    Edit Pharmacy Product
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Pharmacy Product</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('pharmacy.index')}}">Pharmacy</a></li>
                            <li class="breadcrumb-item active">Edit Pharmacy Product</li>
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
                        <h3 class="card-title">Edit Pharmacy Product</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{route('pharmacy.update',[$inventory->branch_id,$inventory->product_id])}}" method="post" autocomplete="off">
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
                                       placeholder="Enter Product Quantity" min="0">
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
                            <div class="form-group">
                                <label for="critical_level">Critical Level</label>
                                <input type="number" class="form-control" id="critical_level" name="critical_level" value="{{$inventory->critical_level ?? old('critical_level')}}"
                                       placeholder="Enter critical_level" min="1">
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

@endsection
