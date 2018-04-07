var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var redis = new Redis();

// io.sockets.on('connection', function(socket) {
	redis.subscribe('channel-name');
	redis.on('message', function(channel, message) {
	    message = JSON.parse(message);
	    var room = message.data.data.thread_id;
	    var data = message.data.data.message;
	    if(typeof(data) == "object" && data != null) {
		    io.emit(channel + ':' + message.event + '\\Room\\' + room, data);
		    io.emit(channel + ':' + message.event + '\\User\\' + data.to_id, data);
	    }else{
		    io.emit(channel + ':' + message.event + '\\Room\\Typing\\' + room, data);
	    }
	});
	redis.on("error", function(err){
	    console.log(err);
	});
// });
http.listen(3000, function(){
    console.log('Listening on Port 3000');
});
