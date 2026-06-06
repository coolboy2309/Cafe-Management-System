<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>

<h2>Register</h2>

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form method="POST" action="{{ route('register') }}">
    @csrf

    <label>Name</label><br>
    <input type="text" name="name"><br><br>

    <label>Password</label><br>
    <input type="password" name="password"><br><br>

    <label>Role</label><br>
    <select name="role">
        <option value="admin">Admin</option>
        <option value="cashier">Cashier</option>
        <option value="waiter">Waiter</option>
        <option value="manager">Manager</option>
    </select><br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>
