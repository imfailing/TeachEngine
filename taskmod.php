<?php
		$sessionid=$_GET['sessionid'];
		include_once("settings.php");		
		$tasklist = mysql_query("SELECT * FROM tasks WHERE id_session = '".$sessionid."'");
		if($tasklist)
		{
		while($task = mysql_fetch_array($tasklist))
			{
				echo "<a href=\"tasks/".$task['filename']."\"><h4>".$task['filename']." - задание</h4></a>".$task['label'];
				if($task['keyword']=='0')
				{
					echo " <b>с вашей проверкой</b><br><br>";
				} else
				{
					echo " <b>ключ:</b>".$task['keyword']."<br><br>";
				}
				
			}
		}
?>
<form id="addtask" action="addtask.php" method="post" enctype="multipart/form-data" onsubmit="return sendForm(this)">
					<fieldset id="inputs">
						<input id="file" type="file" name="file" required>
						<input id="ip" placeholder="Ваш IP:Port" required>
						<input id="nametask" placeholder="Название задания" required>
					</fieldset>
					<br>Требуется ли проверка?  <input id="keyword" type="checkbox" class = "checkbox">
					<fieldset id="actions">
						<input type="submit" id="submit" value="Добавить">
					</fieldset>
				</form>