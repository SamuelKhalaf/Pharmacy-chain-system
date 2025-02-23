@extends('layouts.admin')
@section('title')
    Moderators
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Moderators</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Moderators</li>

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
                                <h3 class="card-title">All Moderators</h3>
                            </div>
                            @include('admin.includes.alerts.success')
                            @include('admin.includes.alerts.errors')
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>name</th>
                                        <th>email</th>
                                        <th>role</th>
                                        <th>branch</th>
                                        <th>control panel</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($moderators))
                                        @foreach($moderators as $moderator)
                                            <tr>
                                                <td>{{$moderator->name}}</td>
                                                <td>{{$moderator->email}}</td>
                                                <td>{{$moderator->role_name}}</td>
                                                <td>{{$moderator->branch_name}}</td>
                                                <td class="project-actions text-right">
                                                    <a class="btn btn-primary btn-sm" href="{{route('moderator.show',$moderator->id)}}">
                                                        <i class="fas fa-folder">
                                                        </i>
                                                        View
                                                    </a>
                                                    <a class="btn btn-info btn-sm" href="{{route('moderator.edit',$moderator->id)}}">
                                                        <i class="fas fa-pencil-alt">
                                                        </i>
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('moderator.destroy', $moderator->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>

                                                </td>
                                            </tr>
                                        @endforeach
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
