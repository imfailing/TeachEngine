<?php

session_start( );

if (isset($_SESSION['username'])) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<title>Ajax Chat</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="imagetoolbar" content="no" />
		<link rel="stylesheet" type="text/css" media="screen" href="include/css/chat.css" />
		<script type="text/javascript" src="include/js/prototype.js"> </script>
		<script type="text/javascript" src="include/js/chatting.js"> </script>
	</head>
	<body>
		<div id="backgroundSheet"></div>
		<div id="contentWrapper">
			<form id="loginForm" action="self" method="post">
				<div id="chatClient">
					<div id="chatHeader">Ajax Chat Client v1.0</div>
					<div id="usernameContainer"></div>
					<div id="chatTextbox">
						<input id="text2Chat" name="text2Chat" class="textbox" type="text" maxlength="255" size="118" value="" />
					</div>
					<div id="messageCenter"></div>
					<div id="chatControls">
						<input id="submitButton" type="button" class="button" value="Send"/>&nbsp;&nbsp;&nbsp;
						<input id="quitButton" type="button" class="button" value="Quit" />
					</div>
				</div>
				<input type="hidden" id="username" name="username" value=" <?php print($_SESSION['username']); ?>" />
			</form>
		</div>
	<body>
</html>
<?php
} else {
	print('Вы должны быть залогинены');
}
?>