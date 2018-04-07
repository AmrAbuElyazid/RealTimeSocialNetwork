<p id="power">0</p>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.4/socket.io.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script>
    //var socket = io('http://localhost:3000');
    var socket = io('http://localhost:3000');
    socket.on("channel-name:App\\Events\\ChatEvent", function(message){
        // increase the power everytime we load test route
        console.log(message)
        $('#power').text(parseInt($('#power').text()) + parseInt(message.data.power));
    });
</script>
