<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{asset('plugins/jqvmap/jqvmap.min.css')}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">
    <style>
        .custom-footer {
            background-color: #f8f9fa;
            color: #007bff;
            font-weight: bold;
            padding: 10px;
            border-top: 2px solid #007bff;
        }
        .custom-footer:hover {
            background-color: #007bff;
            color: white;
        }

        /* notifications */
        .notification-item {
            transition: background 0.3s ease-in-out;
            position: relative;
            text-decoration: none;
            color: #333;
        }

        .notification-item:hover {
            background: #f8f9fa;
        }

        .view-details {
            display: none;
            font-size: 12px;
            color: #007bff;
            font-weight: bold;
            position: absolute;
            bottom: 5px;
            right: 10px;
        }

        .notification-item:hover .view-details {
            display: inline;
        }

    </style>
    @yield('style')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    @include('admin.includes.header')
    @include('admin.includes.sidebar')
    @yield('content')
    @include('admin.includes.footer')
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- DataTables  & Plugins -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.js')}}"></script>
<script>
    $(function () {
        $("#example1").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
<script>
    $(function () {
        $('.select2').select2()
    });
</script>
{{--###################### Start Header ajax and jquery part ########################--}}
{{--###################### this works for the transfer requests after accepted by the admin ########################--}}
@can('super_admin')
<script>
    $(document).ready(function () {

        $(document).on("click", ".dropdown-menu", function (event) {
            event.stopPropagation();
        });

        $(document).on("click", ".toggle-details", function (event) {
            event.stopPropagation();

            let target = $($(this).data("target"));

            // Close all other open collapse elements smoothly
            $(".collapse.show").not(target).collapse("hide");

            // Toggle the clicked one smoothly
            target.collapse("toggle")
        });

        function updateRequestCount() {
            $.ajax({
                url: "{{ route('request.count') }}",
                type: "GET",
                success: function (response) {
                    $("#request-count").text(response.count);
                }
            });
        }

        $(document).on("click", ".cancel-request, .accept-request", function () {
            let requestId = $(this).data("id");
            let requestCard = $("#card_"+requestId);
            let action = $(this).hasClass("accept-request") ? "accept" : "cancel";

            let url = `/dashboard/request/${requestId}/${action}`;

            $.ajax({
                url: url,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function (response) {
                    if (response.success || response.cancel || response.reject) {
                        alert(response.message);

                        console.log("Hiding request row:", `#request_${requestId}`);
                        console.log("Hiding request card:", requestCard);

                        $(`.requests-table tr#request_${requestId}`).fadeOut(1000, function () {
                            $(this).remove();
                        });
                        requestCard.fadeOut(1000, function () {
                            $(this).remove();
                        });
                        updateRequestCount();
                        setTimeout(loadLatestRequests, 5000);
                    }else {
                        alert("Unexpected response. Please try again.");
                    }
                },
                error: function () {
                    alert("Failed to connect to the server.");
                }
            });
        });

        function loadLatestRequests() {
            $.ajax({
                url: "{{ route('request.dropdown') }}",
                type: "GET",
                success: function (requests) {
                    let content = "";
                    if (requests.length > 0) {
                        requests.forEach(request => {
                            let createdAt = moment(request.created_at).fromNow();
                            content += `
                                <div id="card_${request.id}" class="card shadow-sm mb-2 rounded">
                                    <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1"><b>From:</b> ${request.from_branch_name}</h6>
                                            <h6 class="mb-1"><b>To:</b> ${request.to_branch_name}</h6>
                                        </div>
                                        <button class="btn btn-sm btn-outline-secondary toggle-details"
                                                type="button" data-toggle="collapse"
                                                data-target="#details-${request.id}" aria-expanded="false">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </div>
                                    <div id="details-${request.id}" class="collapse">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><b>Product:</b> ${request.product_name}</li>
                                            <li class="list-group-item"><b>Quantity:</b> ${request.quantity}</li>
                                            <li class="list-group-item text-muted">
                                                <i class="far fa-clock"></i> ${createdAt}
                                            </li>
                                            <li class="list-group-item text-center">
                                                <button class="btn btn-success btn-sm accept-request"
                                                        data-id="${request.id}">
                                                    <i class="fas fa-check"></i> Accept
                                                </button>
                                                <button class="btn btn-danger btn-sm cancel-request"
                                                        data-id="${request.id}">
                                                    <i class="fas fa-times"></i> Cancel
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            `;
                        });

                        $(".dropdown-menu #latest-requests + .dropdown-footer").show();
                    } else {
                        content = `
                            <div class="card shadow-sm mb-2 rounded text-center">
                                <div class="card-body p-3">
                                    <h6 class="text-muted mb-1"><i class="fas fa-info-circle"></i> No pending requests</h6>
                                    <p class="text-muted small">All transfer requests have been processed.</p>
                                </div>
                            </div>`;

                        $(".dropdown-menu #latest-requests + .dropdown-footer").hide();
                    }
                    $("#latest-requests").html(content);
                },
                error: function () {
                    $("#latest-requests").html("<p>Failed to load requests.</p>");
                    $(".dropdown-menu #latest-requests + .dropdown-footer").hide();
                }
            });
        }

        loadLatestRequests();
        updateRequestCount();

        $(".dropdown-menu #latest-requests + .dropdown-footer").hide();

        // load notifications for products at critical level
        function loadNotifications() {
            $.ajax({
                url: "{{route('notification.unread')}}",
                method: "GET",
                dataType: "json",
                success: function (data) {
                    let $notificationList = $(".notifications-dropdown .dropdown-menu");
                    let $notificationCount = $(".notifications-dropdown .navbar-badge");

                    if (data.length > 0) {
                        $notificationList.empty();
                        $notificationList.append(`<span class="dropdown-header">${data.length} Notifications</span>`);
                        $.each(data, function (index, notification) {
                            let listItem = `
                            <a href="/dashboard/notification/${notification.id}" class="dropdown-item d-flex align-items-center notification-item">
                                <div class="mr-3">
                                    <i class="fas fa-bell text-warning fa-lg"></i>
                                </div>
                                <div class="w-100">
                                    <p class="mb-1 font-weight-bold">${notification.text}</p>
                                    <small class="text-muted d-block">${notification.created_at}</small>
                                    <span class="view-details">view details</span>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                        `;
                            $notificationList.append(listItem);
                        });
                        $notificationList.append(`<a href="/dashboard/notification" class="dropdown-item dropdown-footer">See All Notifications</a>`);
                        $notificationCount.text(data.length);
                    } else {
                        $notificationList.html('<span class="dropdown-header">No new notifications</span>');
                        $notificationCount.text("0");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error loading notifications:", error);
                }
            });
        }

        loadNotifications();
    });
</script>
@endcan
{{--###################### End Header ajax and jquery part ########################--}}
@yield('scripts')
</body>
</html>
