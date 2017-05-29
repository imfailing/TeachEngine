<?php

session_start( );

require_once('db.inc');

if (isset($_REQUEST['username']) && isset($_REQUEST['lasttime']))

	if ($conn = @mysql_connect(DB_SERVER, DB_USER, DB_PASS)) {
		if (@mysql_select_db(DB_NAME, $conn)) {
			$sql = sprintf('DELETE FROM messages WHERE msg_dte < %s', ($_REQUEST['lasttime'] - 60000));
			@mysql_query($sql);
			$sql = sprintf('SELECT msg_dte, username, message FROM messages m INNER JOIN users u ON m.user_id = u.user_id WHERE msg_dte >= %s ORDER BY msg_dte DESC;', $_REQUEST['lasttime']);
			if ($result = @mysql_query($sql)) {
				while ($row = @mysql_fetch_assoc($result))
					printf("<p%s>[%s] %s: %s</p>\n", (($row['username'] == $_REQUEST['username']) ? ' class="usernameMe"' : ''), $row['msg_dte'], $row['username'], $row['message']);
				@mysql_free_result($result);
			} else
				print('');
		} else
			print('');
		@mysql_close($conn);
	} else
		print('');
else
	print('');
?>