@extends('admin.layout')
@section('title','Admin User')

@section('top')

<div class="block-body text-right">
    <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-primary">Add User</button>
    <!-- Modal-->
    <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><strong id="exampleModalLabel" class="modal-title">Signin Modal</strong>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">

                    <form action="{{url('user_register')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-control-label">Full Name</label>
                            <input type="text" name="flname" placeholder="Full Name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">User Name</label>
                            <input type="text" name="urname" placeholder="User Name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Email</label>
                            <input type="email" name="email" placeholder="Email Address" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Phone Number</label>
                            <input type="text" name="phone" placeholder="Phone Number" class="form-control">
                        </div>
                        <label class="label-material">Select</label>
                        <div class="col-sm-9">
                            <select name="role" class="form-control mb-8 mb-8" validate>
                                <option style="background-color: #2f2f2fff">Select Account Type</option>
                                <option style="background-color: #2f2f2fff" value="casher">Casher</option>
                                <option style="background-color: #2f2f2fff" value="finance">Finance</option>
                                <option style="background-color: #2f2f2fff" value="employee">Employee</option>
                                <option style="background-color: #2f2f2fff" value="manager">Manager</option>
                                <option style="background-color: #2f2f2fff" value="waiter">Waiter</option>
                                <option style="background-color: #2f2f2fff" value="store">Store</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Salary</label>
                            <input type="number" name="salary" placeholder="Salary" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">PassWord</label>
                            <input type="password" name="psword" placeholder="Password" class="form-control">
                        </div>
                        <div class="form-group">

                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                    <input type="submit" value="Signin" class="btn btn-primary">

                </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('content')


<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="block">
                <div><strong>
                        @if(session()->has('message'))
                        {{session()->get('message')}}
                        @endif
                    </strong></div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Phone No</th>
                                <th>Role</th>
                                <th>Salary</th>
                                <th>Credit</th>
                                <th>Active</th>
                                <th>Register Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        @foreach($data as $data)

                        <tbody>
                            <tr>
                                <th>{{$data->name}}</th>
                                <th>{{$data->username}}</th>
                                <th>{{$data->email}}</th>
                                <th>{{$data->phone}}</th>
                                <th>{{$data->role}}</th>
                                <th>{{$data->salary}}</th>
                                <th>{{$data->salary}}</th>
                                <th>
                                    @if($data->active == 1)
                                    <span style="color:green">Active</span>
                                    @endif
                                    @if($data->active == 0)
                                    <span style="color:red">Deactive</span>
                                    @endif
                                </th>
                                <th>{{$data->created_at}}</th>
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