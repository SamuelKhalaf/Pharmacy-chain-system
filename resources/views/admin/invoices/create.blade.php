@extends('layouts.admin')
@section('title')
    New Invoice
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">New Invoice</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('pharmacy.index')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('invoice.index')}}">Invoices</a></li>
                            <li class="breadcrumb-item active">New Invoice</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">New Invoice</h3>
                    </div>
                    @if($users->isNotEmpty())
                        <form action="{{route('invoice.store')}}" method="post" autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="user_id">Select Customer:</label>
                                            <select class="form-control" name="user_id" id="user_id" >
                                                <option value="" disabled selected>-- Select Customer--</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                            <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <h5 class="mt-4">Invoice Items</h5>
                                <div id="products-container">
                                    <div class="product-row row align-items-center mb-2">
                                        <div class="col-md-4">
                                            <label>Item</label>
                                            <select class="form-control product-select" name="product_id[]" >
                                                <option value="">-- Select Product --</option>
                                                @foreach($inventoryProducts as $inventoryProduct)
                                                    <option value="{{ $inventoryProduct->product_id }}" data-quantity="{{ $inventoryProduct->quantity }}">{{ $inventoryProduct->product_name }}</option>
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
                                        <div class="col-md-2 text-center">
                                            <button type="button" class="btn btn-danger remove-product mt-4" disabled >Delete</button>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" id="add-product" class="btn btn-success mt-2">Add Another Item</button>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Create Invoice</button>
                            </div>
                        </form>
                    @else
                        <div class="card-body">
                            <p>There is no <b>Users</b> yet</p>
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
                    alert("You must keep at least one product.");
                }
            });

            $("#products-container").on("change", ".product-select", function () {
                let selectedOption = $(this).find("option:selected");
                let availableQuantity = selectedOption.data("quantity") || 0;
                $(this).closest(".product-row").find('input[name="quantity[]"]').val(availableQuantity);
                updateProductOptions();
            });

            updateProductOptions();
            updateRemoveButtons();
        });
    </script>
@endsection




