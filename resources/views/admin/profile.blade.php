@extends('admin.layout')
@section('title','Admin Profile')

@section('top')

<div class="block-body text-right">
    <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-primary">Change Password</button>
    <!-- Modal-->
    <div id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><strong id="exampleModalLabel" class="modal-title">Signin Modal</strong>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">

                    <form action="{{url('admin_pass',Auth::user()->id)}}" onsubmit="return checkPasswordMatch()" method="POST">
                        @csrf
                        <div class="form-group-material">
                            <input id="password" type="password" name="password" minlength="4" required class="input-material">
                            <label for="password" class="label-material">New Password</label>
                        </div>
                        <div class="form-group-material">
                            <input id="password_confirmation" type="password" name="password_confirmation" minlength="4" required class="input-material">
                            <label for="password_confirmation" class="label-material">
                                Confirm Password
                            </label>

                            <small id="passwordError" style="color:red; display:none;">
                                Passwords do not match
                            </small>
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
        <div class="col-lg-6">
            <div class="user-block block text-center"  style="background-color: #3375f8; color:white">
                <div class="avatar"><img src="img/avatar-1.jpg" alt="..." class="img-fluid">
                    <div class="order dashbg-2">1st</div>
                </div><a href="#" class="user-title">
                    <h3 class="h5" style="text-transform: uppercase;color:white">{{ Auth::user()->name }}</h3><span  style="color:white">{{Auth::user()->email}}</span>
                </a>
                <div class="contributions" style="background-color:white;color: #1a5df9">
                    <h5 style="text-transform: uppercase;"> Role: {{Auth::user()->role}}</h5>
                </div>
                <div class="details d-flex">
                    <!-- <div class="item"><i class="fa fa-money"></i><strong>skdsdsd</strong></div>-->
                    <!-- <div class="item center"><i class="fa fa-gg center"></i><strong>All Controller</strong></div>           -->
                    <!-- <div class="item"><i class="icon-flow-branch"></i><strong>460</strong></div> -->
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="block">
                <div class="title"><strong class="d-block" style="color: #0842ff">Update Your Profile</strong><span class="d-block">
                        @if(session()->has('message'))
                        {{session()->get('message')}}
                        @endif
                    </span></div>
                <div class="block-body" >
                    <form method="POST" action="{{url('profile_update',Auth::user()->id)}}">
                        @csrf
                        <div class="form-group">
                            <label style="color:#0842ff" class="form-control-label">Full Name</label>
                            <input type="text" name="flname" placeholder="Full Name" value="{{Auth::user()->name}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label style="color:#0842ff" class="form-control-label">User Name</label>
                            <input type="text" name="urname" placeholder="User Name" value="{{Auth::user()->username}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label style="color:#0842ff" class="form-control-label">Email</label>
                            <input type="email" name="email" placeholder="Email Address" value="{{Auth::user()->email}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label style="color:#0842ff" class="form-control-label">Phone Number</label>
                            <input type="text" name="phone" placeholder="Phone Number" value="{{Auth::user()->phone}}" class="form-control">
                        </div>

                        <div class="form-group">
                            <input type="submit" value="Submit" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
</div>

<script>
    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirmation').value;
        const error = document.getElementById('passwordError');

        if (password.length < 4) {
            error.innerText = 'Password must be at least 4 characters';
            error.style.display = 'block';
            return false;
        }

        if (password !== confirm) {
            error.innerText = 'Passwords do not match';
            error.style.display = 'block';
            return false;
        }

        error.style.display = 'none';
        return true;
    }
</script>

@endsection