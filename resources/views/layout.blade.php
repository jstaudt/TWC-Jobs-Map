<!DOCTYPE html>
<html lang="en">
<head>
    <meta id="token" name="token" content="{{ csrf_token() }}">
    <meta charset="utf8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
    <link rel="icon" href="/texas_flag.png">
    <title>portal.app</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/app.css')}}">
</head>
<body>
    @yield('content')
    @yield('footer')
    
    <?php
        $key = env('GOOGLE_MAPS_API');
    ?>

    <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="/js/bootstrap-checkbox.min.js" defer></script>
    <script type="text/javascript" src="/js/main.js"></script>
    <script  
    src="https://maps.googleapis.com/maps/api/js?key={{$key}}&callback=window.jobsApp.initMap" async defer>
    </script>
</body>
    
</html>
