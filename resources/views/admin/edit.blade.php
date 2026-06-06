<!DOCTYPE html>
<html>
<base href="/public">
@include('css')

<body>
    @include('admin.head')
    @include('admin.nav')

    <div class="page-content">
        <div class="page-header">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom">Admin User</h2>




            </div>
        </div>
        <div class="container-fluid">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li>
                <li class="breadcrumb-item active">User</li>
                <li class="breadcrumb-item active">Edit</li>
            </ul>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <div class="user-block block text-center">
                        <div class="avatar"><img src="cafe/img/avatar-1.jpg" alt="..." class="img-fluid">
                            <div class="order dashbg-2">1st</div>
                        </div><a href="#" class="user-title">
                            <h3 class="h5">{{$edit->name}}</h3><span>{{$edit->email}}</span>
                        </a>
                        <div class="contributions">{{$edit->role}}</div>
                        <div class="details d-flex">
                            <div class="item"><i class="fa fa-money"></i><strong>{{$edit->salary}}</strong></div>
                            <div class="item"><i class="fa fa-gg"></i><strong>@if($edit->active == 1)
                                            <span class="text-success"><strong>Active</strong></span>
                                            @endif
                                            @if($edit->active == 0)
                                            <span class="text-danger"><strong> Deactive</strong></span>
                                            @endif</strong></div>
                            <div class="item"><i class="icon-flow-branch"></i><strong>460</strong></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="block">
                        <div class="title"><strong class="d-block">Edit User Form</strong><span class="d-block"></span></div>
                        <div class="block-body">
                            <form method="POST" action="{{url('update_user',$edit->id)}}">
                                @csrf
                                <div class="form-group">
                                    <label class="form-control-label">Full Name</label>
                                    <input type="text" name="flname" placeholder="Full Name" value="{{$edit->name}}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">User Name</label>
                                    <input type="text" name="urname" placeholder="User Name" value="{{$edit->username}}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Email</label>
                                    <input type="email" name="email" placeholder="Email Address" value="{{$edit->email}}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Phone Number</label>
                                    <input type="text" name="phone" placeholder="Phone Number" value="{{$edit->phone}}" class="form-control">
                                </div>
                                <label class="label-material">Select</label>
                                <div class="col-sm-9">
                                    <select name="role" class="form-control mb-8 mb-8" validate>
                                        <!-- <option style="background-color: #2f2f2fff">Select Account Type</option> -->
                                        <option style="background-color: #2f2f2fff" value="casher"{{$edit->role == 'casher' ? 'selected' : ''}}>Casher</option>
                                        <option style="background-color: #2f2f2fff" value="finance"{{$edit->role == 'finance' ? 'selected' : ''}}>Finance</option>
                                        <option style="background-color: #2f2f2fff" value="employee"{{$edit->role == 'employee' ? 'selected' : ''}}>Employee</option>
                                        <option style="background-color: #2f2f2fff" value="manager"{{$edit->role == 'manager' ? 'selected' : ''}}>Manager</option>
                                        <option style="background-color: #2f2f2fff" value="store"{{$edit->role == 'store' ? 'selected' : ''}}>Store</option>
                                        <option style="background-color: #2f2f2fff" value="waiter"{{$edit->role == 'waiter' ? 'selected' : ''}}>Waiter</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Salary</label>
                                    <input type="number" name="salary" placeholder="Salary" value="{{$edit->salary}}" class="form-control">
                                </div>
                                
                                <div class="form-group">
                                    <input type="submit" value="Update User" class="btn btn-primary">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- JavaScript files-->
    @include('admin.footer')
</body>

</html>