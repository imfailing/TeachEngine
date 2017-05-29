<?php

session_start();

require_once('db.inc');

$output = '';

if (isset($_REQUEST['username']) && isset($_REQUEST['lasttime']))
	if ($conn = @mysql_connect(DB_SERVER, DB_USER, DB_PASS)) {
		if (@mysql_select_db(DB_NAME, $conn)) {
			$sql = sprintf('DELETE FROM points WHERE pts_dte < %s', ($_REQUEST['lasttime'] - 60000));
			@mysql_query($sql);
			$sql = sprintf('SELECT pts_dte, color, size, points FROM points p INNER JOIN users u ON p.user_id = u.user_id WHERE pts_dte >= %s AND u.username <> %s ORDER BY msg_dte DESC;', $_REQUEST['lasttime'], quote_smart($_REQUEST['username']));
			if ($result = @mysql_query($sql)) {
				$output = '[';
				while ($row = @mysql_fetch_assoc($result)) {
					$output .= sprintf("{ color: '%s', size: %s, points: {", $row['color'], $row['size']);
					$points = split(';', $row['points']);
					for ($i = 0, $il = count($points); $i < $il; $i++) {
						if ($i)
							$output .= ',';
						$output .= '['.$points[$i].']';
					}
					$output .= '}';
					$output .= '}';
				}
				@mysql_free_result($result);
			}
		}
		@mysql_close($conn);
	}
print($output);
?>