@extends('layouts.admin')
@section('title')
    Request Products
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Request Products</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('pharmacy.index')}}">Home</a></li>
                            <li class="breadcrumb-item active">Request Products</li>
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
                        <h3 class="card-title">Request Products</h3>
                    </div>
                    <form action="{{route('request.store')}}" method="post" autocomplete="off">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="request_type">Select what do you want to do</label>
                                        <select class="form-control" name="request_type" id="request_type">
                                            <option value="" selected disabled> Click Here To Select </option>
                                            <option value="send">Send Products</option>
                                            <option value="receive">Receive Products</option>
                                        </select>
                                        @error('request_type')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row branch-container"></div>
                            <div id="products" style="display: none;">
                                <h5 class="mt-2">Products</h5>
                                <div id="products-container">
                                    <div class="product-row row align-items-center mb-2">
                                        <div class="col-md-4">
                                            <label>Product</label>
                                            <select class="form-control product-select" name="product_id[]">
                                                <option value="" selected disabled>-- Select Product --</option>
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
                                            <button type="button" class="btn btn-danger remove-product mt-4" disabled>Delete</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add-product" class="btn btn-success mt-2">Add Another Product</button>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Request Products</button>
                            </div>
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
            let AUTH_USER_BRANCH_ID;

            $.ajax({
                url: "{{ route('auth.branch') }}",
                type: "GET",
                success: function (response) {
                    AUTH_USER_BRANCH_ID = response.branch_id;
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching branch ID:", error);
                }
            });

            function fetchProducts(branchId) {
                if (!branchId) return;

                $.ajax({
                    url: "{{ route('request.get-products') }}",
                    type: "GET",
                    data: { branch_id: branchId },
                    success: function (response) {
                        let productSelect = $("#products-container .product-select");
                        productSelect.empty().append(`<option disabled selected>-- Select Product --</option>`);
                        $.each(response.data, function (index, inventory) {
                            productSelect.append(`<option value="${inventory.product_id}" data-quantity="${inventory.quantity}">${inventory.product_name}</option>`);
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error("An error occurred:", error);
                    }
                });
            }

            function toggleBranchFields() {
                let requestType = $("#request_type").val();
                let branchContainer = $(".branch-container");
                branchContainer.empty();

                if (requestType === "send") {
                    branchContainer.append(`
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="to_branch">To Branch:</label>
                                <select class="form-control" name="to_branch_id" id="to_branch">
                                    <option value="" selected>-- Select Branch --</option>
                                    @foreach($allOtherBranches as $branch)
                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    `);
                    $("#products-container input[name='quantity[]']").val("");
                    $("#products").show();
                    fetchProducts(AUTH_USER_BRANCH_ID);
                } else if (requestType === "receive") {
                    branchContainer.append(`
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="from_branch">From Branch:</label>
                                <select class="form-control" name="from_branch_id" id="from_branch">
                                    <option value="" selected>-- Select Branch --</option>
                                    @foreach($allOtherOldBranches as $branch)
                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    `);
                    $("#products-container .product-select").empty().append(`<option disabled selected>-- Select Branch to see the products --</option>`);
                    $("#products-container input[name='quantity[]']").val("");
                    $("#products").show();
                } else {
                    $("#products").hide();
                }
            }

            $(document).on("change", "#from_branch", function () {
                fetchProducts($(this).val());
            });

            function updateProductOptions() {
                let selectedProducts = $(".product-select").map(function () {
                    return $(this).val();
                }).get();

                $(".product-select").each(function () {
                    let currentValue = $(this).val();
                    $(this).find("option").each(function () {
                        let optionValue = $(this).val();
                        $(this).prop("disabled", optionValue && selectedProducts.includes(optionValue) && optionValue !== currentValue);
                    });
                });
            }

            function updateRemoveButtons() {
                $(".remove-product").prop("disabled", $(".product-row").length === 1);
            }

            $(document).on("change", ".product-select", function () {
                let productQuantity = $(this).find("option:selected").data("quantity") || "";
                $(this).closest(".product-row").find("input[name='quantity[]']").val(productQuantity);
                updateProductOptions();
            });

            $("#add-product").click(function () {
                let productRow = $(".product-row:first").clone();
                productRow.find("input").val("");
                productRow.find("select").val("").find("option").prop("disabled", false);
                productRow.find("small").text("");
                $("#products-container").append(productRow);
                updateProductOptions();
                updateRemoveButtons();
            });

            $(document).on("click", ".remove-product", function () {
                if ($(".product-row").length > 1) {
                    $(this).closest(".product-row").remove();
                    updateProductOptions();
                    updateRemoveButtons();
                } else {
                    alert("You must keep at least one product.");
                }
            });

            $("#request_type").on("change", toggleBranchFields);

            toggleBranchFields();
            updateProductOptions();
            updateRemoveButtons();
        });


    </script>
@endsection

