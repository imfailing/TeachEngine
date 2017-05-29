    <style type="text/css">
		body { font-family:Arial, Helvetica, Sans-Serif; font-size:0.8em;}
        #list { border-collapse:collapse;}
        #list h4 { margin:0px; padding:0px;}
        #list img { float:right;}
        #list ul { margin:10px 0 10px 40px; padding:0px;}
        #list th { background:#7CB8E2 url(header_bkg.png) repeat-x scroll center left; color:#fff; padding:7px 15px; text-align:left;}
        #list td { background:#FFFFFF none repeat-x scroll center left; color:#000; padding:7px 15px; }
        #list tr.odd td { background:#fff url(row_bkg.png) repeat-x scroll center left; cursor:pointer; }
        #list div.arrow { background:transparent url(arrows.png) no-repeat scroll 0px -16px; width:16px; height:16px; display:block;}
        #list div.up { background-position:0px 0px;}
		.green td {background:#ccffcc !important;}
		.yellow td {background:#ffffcc !important;}
		.red td {background:#ff9999 !important}
		.name td {background:#fff url(row_bkg.png); !important}

		</style>
	<table id="list">
	<tr></tr>	
<?php
		$sessionid=$_GET['sessionid'];
		include_once("settings.php");		
		$studentlist = mysql_query("SELECT * FROM participation, users WHERE id_session = '".$sessionid."' AND id_user=users.id AND state!='0'");
		if($studentlist)
		{
			while($student = mysql_fetch_array($studentlist))
			{
				echo "<tr class=\"name\">
					<td>".$student['name']."</td>
					<td>";
					if($student['canchat']==0)
					{
						echo "<img id=\"lol\" src=\"penb.png\">";
					} else
					{
						echo "<img id=\"lol\" src=\"pen.png\">";
					}
					echo "</td>
					<td>";
					if ($student['canpaint']==0)
					{
						echo "<img src=\"whiteboardb.png\">";
					} else
					{
						echo "<img src=\"whiteboard.png\">";
					}
					echo "</td>
					<td><img src=\"leave.png\"></td>
				</tr>
				<tr>
				<td colspan=\"4\">
				<table id=\"tasks\" width=\"100 %\">";
				$tasklist = mysql_query("SELECT * FROM tasksstat, tasks WHERE tasksstat.id_task=tasks.id AND tasks.id_session = '".$sessionid."' AND tasksstat.id_user=".$student['id']);
				while($task = mysql_fetch_array($tasklist))
				{
					echo "<tr "; 
					if($task['state']==-1)
					{
						echo "class=\"red\"><td>";
					} elseif ($task['state']==0)
					{
						echo "class=\"yellow\"><td>";
					}  elseif ($task['state']==1)
					{
						echo "class=\"green\"><td>";
					}
					echo "<h4>".$task['filename']." - задание</h4>".$task['label']."</td></tr>";
				}	
				
				echo "</table>
				</td>
				</tr>";
			}
		}

?>
</table>