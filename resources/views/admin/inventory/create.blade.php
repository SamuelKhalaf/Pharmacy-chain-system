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
                    @if($newBranches->isNotEmpty())
                        <form action="{{route('inventory.store')}}" method="post" autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="branch_id">Select Branch:</label>
                                            <select class="form-control" name="branch_id" id="branch_id" >
                                                <option value="">-- Select Branch --</option>
                                                @foreach($newBranches as $newBranch)
                                                    <option value="{{ $newBranch->id }}">{{ $newBranch->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('branch_id')
                                            <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <h5 class="mt-4">Add Products</h5>
                                <div id="products-container">
                                    <div class="product-row row align-items-center mb-2">
                                        <div class="col-md-4">
                                            <label>Product</label>
                                            <select class="form-control product-select" name="product_id[]" >
                                                <option value="">-- Select Product --</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('product_id.*')
                                            <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label>Qty</label>
                                            <input type="number" class="form-control" name="quantity[]" min="1">
                                            @error('quantity.*')
                                            <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label>Price</label>
                                            <input type="number" class="form-control" name="price[]" min="1" step="0.01">
                                            @error('price.*')
                                            <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-2 text-center">
                                            <button type="button" class="btn btn-danger remove-product mt-4" disabled >Delete</button>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" id="add-product" class="btn btn-success mt-2">Add Another Product</button>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Store Products</button>
                            </div>
                        </form>
                    @else
                        <div class="card-body">
                            <p>There is no <b>New Branches</b> yet</p>
                        </div>
                    @endif

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
                productRow.find("small").text("");
                $("#products-container").append(productRow);

                updateProductOptions();
                updateRemoveButtons();
            });
            function updateRemoveButtons() {
                if ($(".product-row").length === 1) {
                    $(".remove-product").prop("disabled", true);
                } else {
                    $(".remove-product").prop("disabled", false);
                }
            }

            $("#products-container").on("click", ".remove-product", function () {
                if ($(".product-row").length > 1) {
                    $(this).closest(".product-row").remove();
                    updateProductOptions();
                    updateRemoveButtons();
                } else {
                    alert("You must keep at least one product."); // رسالة تنبيه للمستخدم
                }
            });

            $("#products-container").on("change", ".product-select", function () {
                updateProductOptions();
            });

            updateProductOptions();
            updateRemoveButtons();
        });
    </script>
@endsection




