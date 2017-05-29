// Создаем текст сообщений для событий
strings = {
    'connected': '[sys][time]%time%[/time]: Вы успешно присоединились к семинару',
    'userJoined': '[sys][time]%time%[/time]: Пользователь [user]%name%[/user] присоединился к чату.[/sys]',
    'messageSent': '[out][time]%time%[/time]: [user]%name%[/user]: %text%[/out]',
    'messageReceived': '[in][time]%time%[/time]: [user]%name%[/user]: %text%[/in]',
    'userSplit': '[sys][time]%time%[/time]: Пользователь [user]%name%[/user] покинул чат.[/sys]'
};
window.onload = function() {
    socket = io.connect('192.168.1.200:8082');
    socket.on('connect', function () {
		socket.emit('join room', room );
		socket.emit('join teacher', {userid: userid, sessionid: room});
		socket.on('message', function (msg) {
                        document.querySelector('#log').innerHTML += strings[msg.event].replace(/\[([a-z]+)\]/g, '<span class="$1">').replace(/\[\/[a-z]+\]/g, '</span>').replace(/\%time\%/, msg.time).replace(/\%name\%/, msg.name).replace(/\%text\%/, unescape(msg.text).replace('<', '&lt;').replace('>', '&gt;')) + '<br>';
            document.querySelector('#log').scrollTop = document.querySelector('#log').scrollHeight;
       });
	   
		socket.on('whiteboard', function (data) {
			list.clear();
			list.fromJSON(data);
		});
		
		socket.on('refreshlist', function (lol) {
			$.get('studentslist.php', { sessionid: room } , function(data){$("#studentlist").html(data);});
		});
		
		socket.on('add task', function (data) {
			$.get('taskmod.php', { sessionid: room } , function(data){$("#task-content").html(data);});
		});
		
		socket.on('checktask', function (data) {
			$.get('studentslist.php', { sessionid: room } , function(data){$("#studentlist").html(data);});
		});
		
        document.querySelector('#input').onkeypress = function(e) {
            if (e.which == '13') {
                socket.emit('message', escape(document.querySelector('#input').value));
                document.querySelector('#input').value = '';
            }
        };
		
        document.querySelector('#send').onclick = function() {
            socket.send(escape(document.querySelector('#input').value));
            document.querySelector('#input').value = '';
        };		
    });
};