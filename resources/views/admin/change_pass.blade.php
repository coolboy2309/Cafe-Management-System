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
                <li class="breadcrumb-item">User</li>
                <li class="breadcrumb-item active">Change Password</li>
            </ul>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <div class="block">
                        <div class="title"><strong class="d-block">Change Password of {{$user->name}}</strong><span class="d-block"></span></div>
                        <div class="block-body">
                            <form method="POST" action="{{ url('pass_change', $user->id) }}" onsubmit="return checkPasswordMatch()">
                                @csrf

                                <div class="col-sm-9">  

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

                                    <div class="form-group">
                                        <input type="submit" value="Update User" class="btn btn-primary">
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>  

<script>
function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirm  = document.getElementById('password_confirmation').value;
    const error    = document.getElementById('passwordError');

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



    <!-- JavaScript files-->
    @include('admin.footer')
</body>

</html>