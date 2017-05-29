
Event.observe(window, 'load', StartClient);


var g_message = 0;


function StartClient( ) {
	if ($F('username') != '') {
		Event.observe('loginForm', 'submit', SendMessage);
		Event.observe('submitButton', 'click', SendMessage);
		Event.observe('quitButton', 'click', QuitChat);
		new Ajax.PeriodicalUpdater('usernameContainer', 'get_users.php', {
			method: 'post',
			parameters: { username: $F('username') },
			frequency: .5
		});
		g_message = setInterval(AjaxDisplayMessages, 500);
	}
}


var g_lastTime = 0;
var g_onCall = false;


function AjaxDisplayMessages( ) {
		if (!g_onCall) {
		g_onCall = true;
				new Ajax.Request('get_messages.php', {
			method: 'post',
			parameters: { username: $F('username'), lasttime: g_lastTime },
			onSuccess: function (p_xhrResponse) {
				new Insertion.Bottom('messageCenter', p_xhrResponse.responseText);
				var d = new Date( );
				g_lastTime = d.getTime( );
				g_onCall = false;
			},
			onFailure: function( ) {
				new Insertion.Bottom('messageCenter', '<p class="errorMessage">ERROR: Could not retrieve messages.</p>');
				g_onCall = false;
			}
		});
	}
}

function SendMessage(e) {
	Event.stop(e);
	var d = new Date( );
	new Ajax.Request('put_message.php', {
		method: 'post',
		parameters: {
			username: $F('username'),
			message: $F('text2Chat'),
			lasttime: d.getTime( )
		},
		onSuccess: function(p_xhrResponse) {
			$('text2Chat').value = '';
			if (p_xhrResponse.responseText != 1)
				new Insertion.Bottom('messageCenter', '<p class="errorMessage">Не могу послать сообщение.</p>');
		},
		onFailure: function( ) {
			$('text2Chat').value = '';
			new Insertion.Bottom('messageCenter', '<p class="errorMessage">Не могу послать сообщение.</p>');
		}
	});
}

function QuitChat(e) {
	Event.stop(e);
	new Ajax.Request('logout.php', {
		method: 'post',
		parameters: { username: $F('username') },
		onSuccess: function( ) {
			window.location = 'index.html';
		},
		onFailure: function( ) {
			window.location = 'index.html';
		}
	});
}