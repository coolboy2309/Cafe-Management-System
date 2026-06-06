<h1>Casher Dashboard</h1>

<p>Welcome {{ Auth::user()->name }}</p>
<p>Role: {{ Auth::user()->role }}</p>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button>Logout</button>
</form>
