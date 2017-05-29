<?php

session_start( );

require_once('db.inc');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<title>Ajax File Transfer</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	</head>
	<body>
		<div id="contentWrapper">
<?php
if (isset($_REQUEST['nptUsername']) && isset($_REQUEST['nptRecvname']) && isset($_REQUEST['nptLasttime']) && is_uploaded_file($_FILES['nptFile']['tmp_name']))
	if ($conn = @mysql_connect(DB_SERVER, DB_USER, DB_PASS)) {
		if (@mysql_select_db(DB_NAME, $conn)) {
			$sql = sprintf('SELECT username FROM users WHERE username = %s;', $_REQUEST['nptRecvname']);
			$username = '';
			if ($result = @mysql_query($sql)) {
				if ($row = @mysql_fetch_assoc($result))
					$username = $row['username'];
				@mysql_free_result($result);
			}
			if ($username != '') {
				$fileData = file_get_contents($_FILES['nptFile']['tmp_name']);
				$sql = sprintf('INSERT INTO messages (filename, file_data, user_id, file_dte, from_user) VALUES (%s, %s, %s, %s);', quote_smart($_FILES['nptFile']['name']), quote_smart($fileData), $_REQUEST['nptRecvname'], $_REQUEST['nptLasttime'], quote_smart($_REQUEST['nptUsername']));
				@mysql_query($sql);
				print('—оеднение '.$username.'...');
			} else
				print('’ьюстон у нас проблемы'.$username.'.');
		} else
			print('’ьюстон у нас проблемы');
		@mysql_close($conn);
	} else
		print('’ьюстон у нас проблемы');
else
	print('’ьюстон у нас проблемы');
?>
		</div>
	<body>
</html>