<?php
		$sessionid=$_GET['sessionid'];
		$userid=$_GET['userid'];
		include_once("settings.php");		
		$tasklist = mysql_query("SELECT * FROM tasks WHERE id_session = '".$sessionid."'");
		if($tasklist)
		{
		while($task = mysql_fetch_array($tasklist))
			{
				echo "<form id=\"checktask\" onsubmit=\"return check(this)\">";
				echo "<input type=\"hidden\" name=\"taskid\" value=\"".$task['id']."\">";
				echo "<a href=\"tasks/".$task['filename']."\"><h4>".$task['filename']." - скачать задание</h4></a>".$task['label'];
				$peer=mysql_fetch_array(mysql_query("SELECT peer FROM participation WHERE id_user='".$userid."'"));
				echo " <b>IP:Port</b> ".$task['ip']." <b>Ваш peer:</b> ".$peer['peer']."<br>";
				$taskstats = mysql_query("SELECT state FROM tasksstat WHERE id_task = '".$task['id']."' AND id_user='".$userid."'");
				if($task['keyword']=='0')
				{
					if($taskstats)
					{
							
					} else
					{
						echo "<button type=\"submit\" name=\"submit\">Заявить о готовности</button>";
					}
					
				} else
				{
					if($taskstats)
					{
						$tasksstat = mysql_fetch_array($taskstats);
						if($tasksstat['state']==1)
						{
							echo " <B>Задание выполнено верно</b>";
						} elseif($tasksstat['state']==-1)
						{
							echo " <B>Задание выполнено неверно</b><br>";
							echo " <input name=\"keyword\" placeholder=\"Введите ключ\" required><button type=\"submit\" name=\"submit\">Проверить</button><br>";
						} else
						{
							echo " <input name=\"keyword\" placeholder=\"Введите ключ\" required><button type=\"submit\" name=\"submit\">Проверить</button><br>";
						}
					} else
					{
						echo " <input name=\"keyword\" placeholder=\"Введите ключ\" required><button type=\"submit\" name=\"submit\">Проверить</button><br>";
					}
				}
				echo "</form>";
			}
		}
						if(mysql_result($tasklist, 0) == 0) 
				{
					echo "Список заданий пуст";
				}
?>
