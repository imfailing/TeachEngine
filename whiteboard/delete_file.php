<?php
session_start( );

require_once('db.inc');

if (isset($_REQUEST['file_id']))
	if ($conn = @mysql_connect(DB_SERVER, DB_USER, DB_PASS)) {
		if (@mysql_select_db(DB_NAME, $conn)) {
				$sql = 'UPDATE files SET filename = NULL, file_data = NULL, user_id = NULL, file_dte = NULL, from_user = NULL WHERE file_id = '.$_REQUEST['file_id'].';';
				@mysql_query($sql);
			}
		}
		@mysql_close($conn);
	}
?>