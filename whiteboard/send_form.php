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
			<form id="transferForm" action="send_file.php" method="post">
				<div id="formWrapper">
					<label for="nptRecvname">
						Choose a user to send the file to:
					</label>
					<select id="nptRecvname" name="nptRecvname">
						<option value="">&nbsp;</option>
<?php
if ($conn = @mysql_connect(DB_SERVER, DB_USER, DB_PASS)) {
	if (@mysql_select_db(DB_NAME, $conn)) {
		$sql = sprintf('SELECT * FROM users WHERE username <> %s', quote_smart($_SESSION['username']));
		if ($result = @mysql_query($sql)) {
			while ($row = @mysql_fetch_assoc($result))
				printf("<option value=\"%s\">%s</option>\n", $row['user_id'], $row['username']);
			@mysql_free_result($result);
		} else
			print('');
	} else
		print('');
	@mysql_close($conn);
} else
	print('');
?>
					</select>
					<br /><br />
					Выберите файл:
					<input type="file" id="nptFile" name="nptFile" />
					<br /><br />
					<input type="hidden" id="nptUsername" name="nptUsername" value="<?php print($_SESSION['username']); ?>" />
					<script type="text/javascript">
						//<![CDATA[
						var d = new Date( );

						document.writeln('<input type="hidden" id="nptLasttime" name="nptLasttime" value="' + d.getTime( ) + '" />');
						//]]>
					</script>
					<input type="submit" class="button" value="Отправить" />
				</div>
			</form>
		</div>
	<body>
</html>