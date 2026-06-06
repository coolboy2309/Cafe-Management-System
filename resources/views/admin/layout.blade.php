<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <base href="/public">
    @include('css')
    <title>@yield('title')</title>
</head>

<body>
    @include('admin.head')
    @include('admin.nav')


    <div class="page-content">
        <div class="page-header">
            <div class="container-fluid">
                <h2 class="h5 no-margin-bottom"> @yield('title')</h2>

                @yield('top')
            </div>
        </div>
        @yield('content')
    </div>



    <!-- JavaScript files-->
    @include('admin.footer')
    @include('footer')
</body>

</html>