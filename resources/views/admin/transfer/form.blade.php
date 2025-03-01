@extends('layouts.admin')
@section('title')
    Transfer Products
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Transfer Products</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('inventory.index')}}">Inventories</a></li>
                            <li class="breadcrumb-item active">Transfer Products</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.includes.alerts.success')
        @include('admin.includes.alerts.errors')
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Transfer Products</h3>
                    </div>
                    @if($oldBranches->isNotEmpty())
                        <form action="{{route('transfer.store')}}" method="post" autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="from_branch">From Branch:</label>
                                            <select class="form-control" name="from_branch_id" id="from_branch">
                                                <option value="" selected >-- Select Branch --</option>
                                                @foreach($oldBranches as $branch)
                                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                            <small class="text-info alert-message"></small>
                                            @error('from_branch_id')
                                            <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="to_branch">To Branch:</label>
                                            <select class="form-control" name="to_branch_id" id="to_branch">
                                                <option value="" selected >-- Select Branch --</option>
                                                @foreach($allBranches as $branch)
                                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('to_branch_id')
                                            <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <h5 class="mt-2">Products To Transfer</h5>
                                <span class="text-info info-message"> Select branches to see the products.</span>
                                <div id="products-container">
                                    <div class="product-row row align-items-center mb-2">
                                        <div class="col-md-4">
                                            <label>Product</label>
                                            <select class="form-control product-select" name="product_id[]" >
{{--                                                <option value="" selected>-- Select Product --</option>--}}
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

                                <button type="button" id="add-product" class="btn btn-success mt-2">Add Another Product</button>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Transfer Products</button>
                            </div>
                        </form>
                    @else
                        <div class="card-body">
                            <p>There is no <b>Inventories for the Exists Branches</b> yet</p>
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
            $('#from_branch').on('change', function () {
                $('#products-container .product-row').find("input[name='quantity[]']").val('');
                let branch_id = $(this).val();
                if (branch_id !== '') {
                    $.ajax({
                        url: "{{route('transfer.get-products')}}",
                        type: 'GET',
                        data: { 'branch_id': branch_id },
                        success: function (response) {
                            let productSelect = $('#products-container .product-select');
                            if (response.data.length > 0) {
                                productSelect.html('');
                                productSelect.append(`<option disabled selected>-- Select Product --</option>`);
                                $.each(response.data, function (index, inventory) {
                                    let option = `<option value="${inventory.product_id}" data-quantity="${inventory.quantity}">${inventory.product_name}</option>`;
                                    productSelect.append(option);
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('An error occurred:', error);
                        }
                    });
                }
            });
            // Append the quantity to the nearest input of the selected option
            $("#products-container").on("change", ".product-select", function () {
                let selectedOption = $(this).find("option:selected");
                let productQuantity = selectedOption.data("quantity");
                let quantityInput = $(this).closest(".product-row").find("input[name='quantity[]']");

                if (productQuantity !== undefined) {
                    quantityInput.val(productQuantity);
                } else {
                    quantityInput.val("");
                }
            });
            // function to update the options and add disabled property to the selected ones
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
                    $(this).find("option:first").prop("disabled", true);
                });
            }
            // add another product row for the page
            $("#add-product").click(function () {
                let productRow = $(".product-row:first").clone();

                productRow.find("input").val("");
                productRow.find("select").val("");
                productRow.find("select option:first").prop("disabled", true);
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
                updateProductOptions();
            });

            function updateBranchOptions() {
                let fromBranch = $("#from_branch").val();
                let toBranch = $("#to_branch").val();

                $("#to_branch option").prop("disabled", false);
                $("#from_branch option").prop("disabled", false);

                if (fromBranch) {
                    $("#to_branch option[value='" + fromBranch + "']").prop("disabled", true);
                }

                if (toBranch) {
                    $("#from_branch option[value='" + toBranch + "']").prop("disabled", true);
                }
                if(fromBranch && toBranch){
                    $('.info-message').hide();
                }else{
                    $('.info-message').show();
                }
            }

            $("#from_branch, #to_branch").change(function () {
                updateBranchOptions();
            });

            updateProductOptions();
            updateRemoveButtons();
            updateBranchOptions();
        });
    </script>
@endsection




