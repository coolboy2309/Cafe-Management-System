<!DOCTYPE html>
<html>
<base href="/public">
@include('css')

<body>
    <div  class="login-page">
        <div class="container d-flex align-items-center">
            <div class="form-holder has-shadow">
                <div class="row">
                    <!-- Logo & Information Panel-->
                    <div class="col-lg-6">
                        <div class="info d-flex align-items-center">
                            <div class="content">
                                <div class="logo">
                                    <h1>Abenu </h1>
                                </div>
                                <p>
                                    Welcome back! Please login to your account.
                                </p>
                                <hr style="border-color: white; background-color: white; color: white; width: 500px;">
                                <p>@if ($errors->any())
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                @endif</p>
                            </div>
                        </div>
                    </div>
                    <!-- Form Panel    -->
                    <div class="col-lg-6 " style="background-color:white;">
                        <div class="form d-flex align-items-center">
                            <div class="content">
                                <form method="POST" action="{{ route('login') }}" class="form-validate">
                                    @csrf
                                    <div class="form-group">
                                        <input id="login-username" type="text" name="name" required data-msg="Please enter your username" class="input-material">
                                        <label for="login-username" class="label-material">User Name</label>
                                    </div>
                                    <div class="form-group">
                                        <input id="login-password" type="password" name="password" required data-msg="Please enter your password" class="input-material">
                                        <label for="login-password" class="label-material">Password</label>
                                    </div><button type="submit" class="btn btn-primary">Login</button>
                                    <!-- This should be submit button but I replaced it with <a> for demo purposes-->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
    </div>

</body>
@include('footer')



</html>
