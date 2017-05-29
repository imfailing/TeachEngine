<?php

session_start( );

require_once('db.inc');

$filename = 'empty_file.txt';
$file = '';

if (isset($_REQUEST['file_id']))
	if ($conn = @mysql_connect(DB_SERVER, DB_USER, DB_PASS)) {
		if (@mysql_select_db(DB_NAME, $conn)) {
			$sql = 'SELECT filename, file_data FROM files WHERE file_id = '.'mysql_real_escape_string($_REQUEST['file_id']).';';
			if ($result = @mysql_query($sql)) {
				if ($row = @mysql_fetch_assoc($result)) {
					$filename = $row['filename'];
					$file = $row['file_data'];
				}
				@mysql_free_result($result);
				/* Delete the record as the indicator */
				$sql = 'DELETE FROM files WHERE file_id = '.$_REQUEST['file_id'].';';
				@mysql_query($sql);
			}
		}
		@mysql_close($conn);
	}
header('Content-Type: application/octet-stream');
header('Content-Length: '.strlen($file));
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Content-Transfer-Encoding: binary');
/* The following two lines are for IE bug fixes over SSL */
header('Pragma: public');
header('Cache-Control: public, must-revalidate');
print($file);
?>