<?php

session_start( );

require_once('db.inc');

if (isset($_REQUEST['username'])) {
	if ($conn = @mysql_connect(DB_SERVER, DB_USER, DB_PASS)) {
		if (@mysql_select_db(DB_NAME, $conn)) {
			$sql = 'SELECT username FROM users ORDER BY username ASC;';
			if ($result = @mysql_query($sql)) {
				print('<ul>');
				while ($row = @mysql_fetch_assoc($result))
					printf('<li%s>%s</li>', (($row['username'] == $_REQUEST['username']) ? ' class="usernameMe"' : ''), $row['username']);
				print('</ul>');
				@mysql_free_result($result);
			} else
				print('');
		} else
			print('');
		@mysql_close($conn);
	} else
		print('');
} else
	print('');
?>