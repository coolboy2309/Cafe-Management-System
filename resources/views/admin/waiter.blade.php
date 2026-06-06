@extends('admin.layout')
@section('title','Admin Waiter')

@section('content')


<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="block">
                <div><strong>
                    </strong></div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>User Name</th>
                                <th>Credit</th>
                                <th>Active</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        @foreach($wai as $data)

                        <tbody>
                            <tr>
                                <th>{{$data->name}}</th>
                                <th>{{$data->username}}</th>
                                <th>{{$data->salary}}</th>
                                <th>
                                    @if($data->active == 1)
                                    <span style="color:green">Active</span>
                                    @endif
                                    @if($data->active == 0)
                                    <span style="color:red">Deactive</span>
                                    @endif
                                </th>
                                <td><button data-toggle="dropdown" type="button" class="btn btn-outline-secondary dropdown-toggle"><span class="caret"></span>:</button>
                                    <div class="dropdown-menu">

                                        <a href="{{url('edit_user',$data->id)}}" class="dropdown-item">Edit</a>

                                        <a href="{{url('delete_user',$data->id)}}" class="dropdown-item">Delete</a>
                                        @if($data->active == 0)
                                        <a href="{{url('active',$data->id)}}" class="dropdown-item">Active</a>
                                        @endif
                                        @if($data->active == 1)
                                        <a href="{{url('deactive',$data->id)}}" class="dropdown-item">Deactive</a>
                                        @endif


                                        <a href="{{url('change_pass',$data->id)}}" class="dropdown-item">Password Change</a>
                                        <a href="" class="dropdown-item">Blah</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="#" class="dropdown-item">Separated link</a>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection