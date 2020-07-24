<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{env('APP_NAME','Challenge_Panel')}}</title>

    <link rel="stylesheet" href="{{mix('/css/app.css')}}"/>
</head>
<body>
<div id="app"></div>
<script>
    window.username = '{{Auth::user()->name}}';
</script>
<script src="{{mix('/js/app.js')}}"></script>
</body>
</html>
