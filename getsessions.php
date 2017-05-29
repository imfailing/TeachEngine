 <script type="text/javascript">  
        $(document).ready(function(){
		$(".sessiontable tr:odd").addClass("odd");
        $(".sessiontable tr:not(.odd)").hide();
        $(".sessiontable tr:first-child").show();
           
        $(".sessiontable tr.odd").click(function(){
			$(this).next("tr").toggle();
			$(this).find(".arrow").toggleClass("up");
        });
		});
		$("#addsessinbutton").fancybox({
            'width': 800,
            'height': 600,
            'enableEscapeButton' : false,
            'overlayShow' : true,
            'overlayOpacity' : 0,
            'hideOnOverlayClick' : false,
			'titleShow'     : true,
			'transitionIn'  : 'elastic',
			'transitionOut' : 'elastic',
			'onClosed': function(){
				$('#add').trigger( 'reset' );
				$('#adduser').trigger( 'reset' );
				$("#errors").html("");
			}
		});
    </script> 
<table class="sessiontable">
    <thead>
    <tr>
        <th>Дата</th>        
        <th>Тема</th>
        <th>Автор</th>
		<th>Пользователей</th>
    </tr>
    </thead>
	<?php
		include_once("settings.php");		
		$userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE id = '".intval($_COOKIE['id'])."' LIMIT 1"));
		$state=$_GET['state'];
		$limit=$_GET['limit'];
		$sessions = mysql_query("SELECT session.id as sessionid, session.date, session.time, session.topic, session.maxusers, session.maxusers, session.description, session.author, users.id, users.name  FROM session, users WHERE session.author=users.id AND session.state='".$state."' LIMIT 0,".$limit.";");
		if($sessions)
		{
			while($session = mysql_fetch_array($sessions))
			{
				echo "
				<tr>
				<td width=\"1 px\">".$session['date']." ".$session['time']."</td>        
				<td width=\"100 %\"><b>Вебинар: </b> ".$session['topic']."</td>        
				<td width=\"1 px\">".$session['name']."</td>        
				<td width=\"1 px\">".$session['maxusers']."</td>        				
				</tr>
				<tr>
				<td colspan=\"4\"><b>Описание: </b> ".$session['description'];
				if($userdata['id']==$session['author'] && $state==3)
				{
						echo "<p align=\"right\"><a href=\"sessionmod.php?id=".$session['sessionid']."\" class=\"button\">Зайти в семинар</a>";
						echo "<a href=\"sessionmod.php?id=".$session['sessionid']."\" class=\"button edit\">Редактировать семинар</a>";
						echo "<a href=\"sessionmod.php?id=".$session['sessionid']."\" class=\"button delete\">Удалить семинар</a></p>";
				}
				if($userdata['type']==0 && $state==3)
				{
						echo "<p align=\"right\"><a href=\"session.php?id=".$session['sessionid']."\" class=\"button\">Зайти в семинар</a>";
						echo "<a href=\"sessionmod.php?id=".$session['sessionid']."\" class=\"button edit\">Редактировать семинар</a>";
						echo "<a href=\"sessionmod.php?id=".$session['sessionid']."\" class=\"button delete\">Удалить семинар</a></p>";
				}
				if($userdata['type']==1 && $state==2)
				{
						echo "<p align=\"right\"><a href=\"sessionmod.php?id=".$session['sessionid']."\" class=\"button\">Зайти в семинар</a>";
				}
				if(($userdata['type']==2 || $userdata['type']==0) && $state==2)
				{
						echo "<p align=\"right\"><a href=\"session.php?id=".$session['sessionid']."\" class=\"button\">Зайти в семинар</a>";
				}
				if($state==1)
				{
						echo "<p align=\"right\"><a href=\"sessionwatch.php?id=".$session['sessionid']."\" class=\"button\">Посмотреть семинар</a>";
				}
				"</td>
				</tr>
				";
			}
		}
		echo "<tr><td colspan=\"4\" class=\"nothover\">";
		if(mysql_result($sessions, 0) == 0) 
		{
			echo "<center>К сожалению, семинары соответствующие выбранному критерию отсутствуют.</center>";
		}
		if($userdata['type'] == 1)
		{
			echo "<center><a href=\"#addsessionform\" id=\"addsessinbutton\" class=\"button add\">Добавить семинар</a></center>";
		}
		echo "</td></tr>";
	?>
</table>

