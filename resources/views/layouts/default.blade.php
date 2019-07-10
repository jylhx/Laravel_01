<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Larevel01') </title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>

@include('layouts._header')


<div class="container">
    @yield('content')
</div>
</body>
</html>