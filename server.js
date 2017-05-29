var io = require('socket.io').listen(8082); 
io.set('log level', 1);
var json;
var sys = require('util'), mysql = require('mysql-libmysqlclient'), conn, result, row, rows, login,key;

conn = mysql.createConnectionSync();
conn.connectSync("localhost", "root", "wo8pvdtz", "training");

if (!conn.connectedSync()) {
  sys.puts("Connection error " + conn.connectErrno + ": " + conn.connectError);
  process.exit(1);
}

	function randomString(length) {
	var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz'.split('');

	if (! length) {
	length = Math.floor(Math.random() * chars.length);
	}

	var str = '';
	for (var i = 0; i < length; i++) {
	str += chars[Math.floor(Math.random() * chars.length)];
	}
	return str;
	}

io.sockets.on('connection', function (socket) {
	var time = (new Date).toLocaleTimeString();	
	
	socket.on('join teacher', function (data) {
		conn.realQuerySync("UPDATE session SET state = 2 WHERE id ="+data['sessionid']);
		conn.realQuerySync("SELECT name FROM users WHERE id='"+data['userid']+"';");
		result = conn.storeResultSync();
		row = result.fetchArraySync();
		socket.set('name', row[0]);
		socket.set('userid', data['userid']);
	});
	
	socket.on('join student', function (data) {
		var peer=1;
		conn.realQuerySync("SELECT name FROM users WHERE id='"+data['userid']+"';");
		result = conn.storeResultSync();
		row = result.fetchArraySync();
		socket.set('name', row[0]);
		socket.set('userid', data['userid']);
		conn.realQuerySync("SELECT peer FROM participation WHERE id_session='"+data['sessionid']+"';");
		result = conn.storeResultSync();
		while(row = result.fetchArraySync())
		{
			if (row[0]!=peer)
			{
				break;
			}
			peer++;
		}
		result.freeSync();
		conn.realQuerySync("SELECT * FROM participation WHERE id_user='"+data['userid']+"';");
		result = conn.storeResultSync();
		row = result.fetchArraySync();
		if(row == false)
		{
			conn.realQuerySync("INSERT INTO participation (id_user, id_session, state, canpaint, canchat, peer) VALUES ("+data['userid']+","+data['sessionid']+",1,0,1,"+peer+");");
			socket.emit('joined', {state: 1, canpaint: 0, canchat: 1, peer: peer});
		} else
		{
			conn.realQuerySync("UPDATE participation SET state = 1 WHERE id ="+data['userid']);
			socket.emit('joined', {state: 1, canpaint: row[3], canchat: row[4], peer: row[6]});
		}
		result.freeSync();
		socket.get('room', function(err, room) {
			socket.broadcast.to(room).emit('refreshlist', {});
		})		
	});
	
	 socket.on('join room', function (room) {
		socket.set('room', room, function() { console.log('Seminar # ' + room + ' opened'); } );
		socket.join(room);
		socket.get('name', function(err, name) {
			socket.emit('message', {'event': 'connected', 'time': time});
		})
	});

	socket.on('state', function (data) {
			socket.get('room', function(err, room) {
				socket.broadcast.to(room).emit('state', data);
			})
	});
	
	socket.on('add task', function (data) {
		if(data['key']==true)
		{
			key=0;
		} else
		{
			key=randomString(10);
		}
		conn.realQuerySync("INSERT INTO tasks (id_session, filename, ip, label, keyword) VALUES ("+data['sessionid']+",'"+data['filename']+"','"+data['ip']+"','"+data['nametask']+"','"+key+"');");
		conn.realQuerySync("SELECT LAST_INSERT_ID();");
		result = conn.storeResultSync();
		row = result.fetchArraySync()
		result.freeSync();


		socket.get('room', function(err, room) {
			socket.broadcast.to(room).emit('add task', {id: row[0], filename: data['filename'], ip: data['ip'], nametask: data['nametask']});
			socket.emit('add task',{});
		})
	});
	
	socket.on('checktask', function (data) {
		conn.realQuerySync("SELECT keyword FROM tasks WHERE id='"+data['id']+"';");
		result = conn.storeResultSync();
		row = result.fetchArraySync()
		if(data['key']==row[0])
		{
			socket.get('userid', function(err, id) {
				conn.realQuerySync("SELECT state FROM tasksstat WHERE id_task = "+data['id']+" AND id_user="+id);
				result = conn.storeResultSync();
				row = result.fetchArraySync();
				if(row == false)
				{
					conn.realQuerySync("INSERT INTO tasksstat (id_user, id_task, state) VALUES ("+id+","+data['id']+",'1');");	
				} else
				{
					conn.realQuerySync("UPDATE tasksstat set state = 1 WHERE id_user="+id+" AND id_task="+data['id']);	
				}
				
				console.log('fuckyou');
			})

		} else
		{
			
			socket.get('userid', function(err, id) {
				conn.realQuerySync("SELECT state FROM tasksstat WHERE id_task = "+data['id']+" AND id_user="+id);
				result = conn.storeResultSync();
				row = result.fetchArraySync();
				if(row == false)
				{
					conn.realQuerySync("INSERT INTO tasksstat (id_user, id_task, state) VALUES ("+id+","+data['id']+",'-1');");	
				} else
				{
					conn.realQuerySync("UPDATE tasksstat set state = -1 WHERE id_user="+id+" AND id_task="+data['id']);	
				}
				
				console.log('NOOOOOOOO');
			})
		}
		socket.get('room', function(err, room) {
			socket.broadcast.to(room).emit('checktask',{});
			socket.emit('checktask',{});
		})
	});
	
	socket.on('message', function (msg) {
		socket.get('room', function(err, room) {
			var time = (new Date).toLocaleTimeString();
			socket.get('userid', function(err, userid) {
				conn.realQuerySync("INSERT INTO chat (id_session, id_user, text) VALUES ("+room+","+userid+",'"+msg+"');");		
			})
			socket.get('name', function(err, name) {
				socket.json.send({'event': 'messageSent', 'name': name, 'text': msg, 'time': time});
				socket.broadcast.to(room).emit('message', {'event': 'messageReceived', 'name': name, 'text': msg, 'time': time});
		})
    })
	});
	
	socket.on('whiteboard', function (data) {
		socket.get('room', function(err, room) {
			socket.broadcast.to(room).emit('whiteboard', data);
		})
    });
    
    socket.on('disconnect', function() {
		socket.get('room', function(err, room) {
				socket.get('userid', function (err, userid) {
				conn.realQuerySync("UPDATE participation SET state = 0 WHERE id ="+userid);
			  });
			  socket.broadcast.to(room).emit('refreshlist', {});
			});
		});
	
});
