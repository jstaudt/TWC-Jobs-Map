<!DOCTYPE html>
<html lang="en">
<head>
    <meta id="token" name="token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
    <link rel="icon" href="/mapIcon.png">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <title>mapexcel.com</title>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/app.css')}}">
    {{-- TO DO: use elixir to mix in css/map.cs --}}
    <link rel="stylesheet" type="text/css" href="/css/map.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
</head>
<body>
    @yield('content')
    @yield('footer')
    
    <?php
        $key = env('GOOGLE_MAPS_API');
    ?>

    <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script type="text/javascript" src="/js/main.js"></script>
    <script type="text/javascript" src="/js/scrollreveal.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script type="text/javascript" src="/js/creative.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script  
    src="https://maps.googleapis.com/maps/api/js?key={{$key}}&callback=window.jobsApp.initMap" async defer>
    </script>
</body>
    
</html>
