@extends('layouts.admin')
@section('title')
    New Inventory Products
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">New Inventory Products</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('branch.index')}}">Inventories</a></li>
                            <li class="breadcrumb-item active">New Inventory Products</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">New Inventory Products</h3>
                    </div>

                    <form action="{{route('inventory.store')}}" method="post" autocomplete="off">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="branch_id">Select Branch:</label>
                                        <select class="form-control" name="branch_id" id="branch_id" required>
                                            <option value="">-- Select Branch --</option>
                                            @foreach($branches as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-4">Add Products</h5>
                            <div id="products-container">
                                <div class="product-row row align-items-center mb-2">
                                    <div class="col-md-4">
                                        <label>Product</label>
                                        <select class="form-control product-select" name="product_id[]" required>
                                            <option value="">-- Select Product --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Qty</label>
                                        <input type="number" class="form-control" name="quantity[]" min="1" required>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Price</label>
                                        <input type="text" class="form-control" name="price[]" required>
                                    </div>

                                    <div class="col-md-2 text-center">
                                        <button type="button" class="btn btn-danger remove-product mt-4">Delete</button>
                                    </div>
                                </div>
                            </div>

                            <button type="button" id="add-product" class="btn btn-success mt-2">Add Another Product</button>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            function updateProductOptions() {
                let selectedProducts = [];

                $(".product-select").each(function () {
                    let selectedVal = $(this).val();
                    if (selectedVal) {
                        selectedProducts.push(selectedVal);
                    }
                });

                $(".product-select").each(function () {
                    let currentValue = $(this).val();
                    $(this).find("option").each(function () {
                        if ($(this).val() !== "" && selectedProducts.includes($(this).val()) && $(this).val() !== currentValue) {
                            $(this).prop("disabled", true);
                        } else {
                            $(this).prop("disabled", false);
                        }
                    });
                });
            }

            $("#add-product").click(function () {
                let productRow = $(".product-row:first").clone();

                productRow.find("input").val("");
                productRow.find("select").val("");
                $("#products-container").append(productRow);

                updateProductOptions();
            });

            $("#products-container").on("click", ".remove-product", function () {
                $(this).closest(".product-row").remove();
                updateProductOptions();
            });

            $("#products-container").on("change", ".product-select", function () {
                updateProductOptions();
            });

            updateProductOptions();
        });
    </script>
@endsection




