
function CheckUsername( ) {
	if ($F('nptUsername') != '') {
		new Ajax.Request('login.php', {
			method: 'post',
			parameters: { username: $F('nptUsername') },
			onSuccess: function(p_xhrResponse) {
				switch (p_xhrResponse.responseText) {
					case '1':
						alert('Welcome ' + $F('nptUsername') + '.');
						window.location = 'chat.php';
						break;
					case '2':
						alert('');
						$('nptUsername').focus( );
						break;
					case '3':
						alert('');
						$('nptUsername').focus( );
						break;
					case '4':
					default:
						alert('');
						$('nptUsername').focus( );
						break;
				}
				return (false);
			},
			onFailure: function(p_xhrResponse) {
				alert('' + p_xhrResponse.statusText);
				$('nptUsername').focus( );
				return (false);
			}
		});
	} else {
		alert('');
		return (false);
	}
}