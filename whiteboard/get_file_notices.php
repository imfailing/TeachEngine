<?php

session_start( );

require_once('db.inc');

if (isset($_REQUEST['username']) && isset($_REQUEST['lasttime']))
	if ($conn = @mysql_connect(DB_SERVER, DB_USER, DB_PASS)) {
		if (@mysql_select_db(DB_NAME, $conn)) {
			$sql = sprintf('DELETE FROM files WHERE file_dte < %s AND user_id = ', ($_REQUEST['lasttime'] - 60000), quote_smart($_REQUEST['username']));
			@mysql_query($sql);
			$sql = sprintf('SELECT from_user, file_id, filename FROM files f INNER JOIN users u ON f.user_id = u.user_id WHERE msg_dte >= %s AND f.user_id = %s ORDER BY msg_dte DESC;', $_REQUEST['lasttime'], quote_smart($_REQUEST['username']));
			if ($result = @mysql_query($sql)) {

				if ($row = @mysql_fetch_assoc($result))
					printf("['%s', %s, '%s']", $row['from_user'], $row['file_id'], $row['filename']);
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