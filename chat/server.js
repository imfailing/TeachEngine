 var io = require('socket.io').listen(8080); 
 io.set('log level', 1);
 
io.sockets.on('connection', function (socket) {
	var ID = (socket.id).toString().substr(0, 5);
    var time = (new Date).toLocaleTimeString();
	
	 socket.on('join room', function (room) {
		socket.set('room', room, function() { console.log('Seminar # ' + room + ' opened'); } );
		socket.join(room);
		socket.emit('message', {'event': 'connected', 'name': ID, 'time': time});
	})

	
	
    socket.on('message', function (msg) {
		socket.get('room', function(err, room) {
			var time = (new Date).toLocaleTimeString();
			socket.json.send({'event': 'messageSent', 'name': ID, 'text': msg, 'time': time});
			socket.broadcast.to(room).emit('message', {'event': 'messageReceived', 'name': ID, 'text': msg, 'time': time});
		})
    });
	
	socket.on('whiteboard', function (data) {
		socket.get('room', function(err, room) {
			socket.broadcast.to(room).emit('whiteboard', data);
		})
    });
    
    socket.on('disconnect', function() {
		socket.get('room', function(err, room) {
			var time = (new Date).toLocaleTimeString();
			socket.broadcast.to(room).emit('message', {'event':'userSplit', 'name':ID, 'time':time});
		})
    });
});