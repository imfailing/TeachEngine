<?php

session_start( );

require_once('db.inc');

if (isset($_REQUEST['file_id']))
	if ($conn = @mysql_connect(DB_SERVER, DB_USER, DB_PASS)) {
		if (@mysql_select_db(DB_NAME, $conn)) {
			$sql = 'SELECT * FROM files WHERE file_id = '.$_REQUEST['file_id'].';';
			if ($result = @mysql_query($sql)) {
				if ($row = @mysql_fetch_assoc($result)) {
					$filename = $row['filename'];
					$file = $row['file_data'];
					$user_id = $row['user_id'];
					$file_dte = $row['file_dte'];
					$from = $row['from_user'];
					if ($filename == null && $file == null && $user_id == null && $file_dte == null && $from == null)
						print(0);
					else
						print(1);
				} else
					print(0);
				@mysql_free_result($result);
			} else
				print(-1);
		} else
			print(-1);
		@mysql_close($conn);
	} else
		print(-1);
else
	print(-1);
?>