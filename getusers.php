 <script type="text/javascript">  
		$("a.button").fancybox({
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
<table class="usertable">
    <thead>
    <tr>
        <th>Имя</th>        
        <th>Тип</th>
        <th>E-mail</th>
    </tr>
    </thead>
	<?php
		$usertype[0]='администратор';
		$usertype[1]='преподаватель';
		$usertype[2]='студент';
		
		include_once("settings.php");		
		$userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE id = '".intval($_COOKIE['id'])."' LIMIT 1"));
		$limit=$_GET['limit'];
		$users = mysql_query("SELECT * FROM users LIMIT 0,".$limit.";");
		if($users)
			{
			while($user = mysql_fetch_array($users))
			{
				echo "
				<tr>
				<td width=\"60 %\">".$user['name']."</td>        
				<td width=\"20 %\">".$usertype[$user['type']]."</td>        
				<td width=\"20 %\">".$user['email']."</td>        				
				</tr>";
			}
		}
		if($userdata['type']==0)
		{
			echo "<tr>
			<td colspan=\"4\" class=\"lol\"><center><a href=\"#adduserform\" class=\"button add\">Добавить пользователя</a></center></td>
			</tr>";
		}		
	?>
</table>

