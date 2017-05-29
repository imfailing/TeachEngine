<?php

session_start( );

require_once('db.inc');


if (isset($_REQUEST['username']) && isset($_REQUEST['message']) && isset($_REQUEST['lasttime']))
	if ($conn = @mysql_connect(DB_SERVER, DB_USER, DB_PASS)) {
		if (@mysql_select_db(DB_NAME, $conn)) {
			$sql = sprintf('SELECT user_id FROM users WHERE username = %s;', quote_smart($_REQUEST['username']));
			$user_id = -1;
			if ($result = @mysql_query($sql)) {
				if ($row = @mysql_fetch_assoc($result))
					$user_id = $row['user_id'];
				@mysql_free_result($result);
			}
			if ($user_id != -1) {
				$sql = sprintf('INSERT INTO messages (message, user_id, msg_dte) VALUES (%s, %s, %s);', quote_smart($_REQUEST['message']), $user_id, $_REQUEST['lasttime']);
				@mysql_query($sql);
				print(1);
			} else
				print(0);
		} else
			print(0);
		@mysql_close($conn);
	} else
		print(0);
else
	print(0);
?>