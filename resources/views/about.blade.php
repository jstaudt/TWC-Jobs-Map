@extends('layout')

@section('content')

<div id='container'>
    <div class='hero-unit'>
        <div class='hero-header'>
            <h1 style='margin-top:0px;'>Lorem Ipsum</h1>
            <p>Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum</p>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-4'>
        </div>
        <div class='col-md-4 text-center'>
            <div style="padding: 20px;">
                <a href="#" class="thumbnail">
                  <img src="../../work.jpg" alt="working man" style="width: 150px; height: 150px;margin:0px;">
                </a>
            </div>
        </div>
        <div class='col-md-4'>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-4'>
        </div>
        <div class='col-md-4 text-center'>
            <div style="padding: 20px;">
                <a href="#" class="thumbnail" >
                  <img src="../../work.jpg" alt="working man" style="width: 150px; height: 150px;margin:0px;">
                </a>
            </div>
        </div>
        <div class='col-md-4'>
        </div>
    </div>
    <div class='row'>
        <div class='col-md-4'>
        </div>
        <div class='col-md-4 text-center'>
            <div style="padding: 20px;">
                <a href="#" class="thumbnail">
                  <img src="../../work.jpg" alt="working man" style="width: 150px; height: 150px;margin:0px;">
                </a>
            </div>
        </div>
        <div class='col-md-4'>
        </div>
    </div>
    <div class='col-md-12 text-center'>
        <iframe id='chat' src="../cards" height="500" width="765"></iframe>
    </div>
</div>
@stop

@section('footer')
<script src="//developer.appear.in/scripts/appearin-sdk.0.0.4.min.js"></script>
<script
  src="https://code.jquery.com/jquery-3.1.1.slim.min.js"
  integrity="sha256-/SIrNqv8h6QGKDuNoLGA4iret+kyesCkHGzVUUV0shc="
  crossorigin="anonymous"></script>
<script type="text/javascript">
    var appearin = new AppearIn();
    var isWebRtcCompatible = appearin.isWebRtcCompatible();
    appearin.getRandomRoomName(function (err, roomName) {
        console.log(roomName);
        var iframe = document.getElementById("chat");
        //var roomName = roomName;
        appearin.addRoomToIframe(iframe, roomName);
    });
</script>
@stop
