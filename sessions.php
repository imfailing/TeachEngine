<?php
include 'auth.php'
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="charset=utf-8">
    <title>Интерактивный тренинг</title>
<link rel="stylesheet" href="/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
<link rel="stylesheet" href="sessions.css" type="text/css" media="screen" />
<link rel="stylesheet" href="jquery-ui.css" type="text/css" media="screen" />
</head>

<body>

<script src="jquery.js"></script>
<script src="jquery-ui.js"></script>
<script src="jquery.ui.datepicker-ru.js"></script>
<script src="jExpand.js"></script>
<script type="text/javascript" src="/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="/fancybox/jquery.easing-1.4.pack.js"></script>

<div class="info message">
		 <h3>Добро пожаловать!</h3>
		 <p>Здравствуйте.</p>
</div>
<div class="error message">
		 <h3>Ошибка добавления семинара!</h3>
		 <p>Попробуйте еще раз.</p>
</div>
<div class="success message">
		 <h3>Семинар добавлен успешно!</h3>
		 <p>Вы можете продолжить работу с системой.</p>
</div>

<ul id="tabs">
    <li><a href="getsessions.php?state=2&limit=20">Активные семинары</a></li>
    <li><a href="getsessions.php?state=3&limit=20">Запланированные семинары</a></li>
    <li><a href="getsessions.php?state=1&limit=20">Прошедшие семинары</a></li>
	<li><a href="getsessions.php?state=1&limit=20">Мои семинары</a></li>
	<li><a href="getusers.php?limit=20">Пользователи</a></li>
	<li><a href="logoff.php">Выход</a></li>
</ul>

<div id="content">
</div>
<div  style="display:none">
<div id="addsessionform">
  	<form id="add" method="POST" action="">
		<h1>Добавить семинар</h1>
		<input type="text" name="topic" placeholder="Тема" required="">
		<input id="datepicker" type="text" name="date" placeholder="Дата" required="" readonly="readonly">
		<input type="text" name="time" placeholder="Время начала" required="">
		<input type="text" name="maxusers" placeholder="Количество участников" required="">		
		<textarea rows="4" cols="40" name="description" placeholder="Описание"></textarea>		
		<button type="submit" name="submit">Добавить семинар</button>	
	</form>
</div>
<div id="adduserform">
   <form id="adduser" method="POST" action="">
    <h1>Добавить пользователя</h1>
	<div id="errors"></div>
	<input type="text" name="login"  placeholder="Логин" required="">
	<input type="password" name="password" placeholder="Пароль"  required="">
	<input name="name" type="text" placeholder="Имя"  required="">
   	<input name="email" type="email" placeholder="E-mail"  required=""><br>    
	<select name="type" size="1">
	<option value="0">Администратор</option>
	<option value="1">Преподаватель</option>
 	<option selected="2" value="2">Студент</option>  
 	</select> 
	<button type="submit" name="submit">Добавить пользователя</button>
	</form>
</div>

</div>
  <script type="text/javascript">
    var myMessages = ['info','error','success'];
	var limit=20;
	
	function hideAllMessages()
	{
		 var messagesHeights = new Array();
	 
		 for (i=0; i<myMessages.length; i++)
		 {
				  messagesHeights[i] = $('.' + myMessages[i]).outerHeight(); 
				  $('.' + myMessages[i]).css('top', -messagesHeights[i]); 	  
		 }
	}
	
	function showMessage(type)
	{
	$('.'+ type +'-trigger').click(function(){
		  hideAllMessages();				  
		  $('.'+type).animate({top:"0"}, 500);
	});
	}
	
	
    function resetTabs(){
		$("#content").hide(); 
        $("#tabs a").attr("id","");    
    }
	

	function loadcontent(url,limit)
	{
		$.get(url, function(data) {$("#content").html(data);$("#content").fadeIn();});	
	}
	
	
	$(document).ready(function () {
		loadcontent('getsessions.php?state=2',limit);
		$( "#datepicker" ).datepicker( {minDate: '0'});
		$( "#datepicker" ).datepicker( $.datepicker.regional[ "ru" ] );
		$( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
				 
		 hideAllMessages();

		 for(var i=0;i<myMessages.length;i++)
		 {
			showMessage(myMessages[i]);
		 }

		 $('.message').click(function(){			  
				  $(this).animate({top: -$(this).outerHeight()}, 500);
		  });
	});

    (function(){
        $("#tabs li:first a").attr("id","current"); // Activate first tab
		$("#tabs a").on("click",function(event) {
            event.preventDefault();
            if ($(this).attr("id") == "current"){ //detection for current tab
             return       
            }
            else{             
				resetTabs();
				$(this).attr("id","current"); // Activate this				
				url=$(this).attr("href");
				loadcontent(url,limit);
            }
        });
    })()
	
	$('#add').submit(function(){
		$.ajax({
		type: "POST",
		url: "addwebinar.php",
		data: $("#add").serialize(),
		success: function(){
			parent.$.fancybox.close();
			$('.success h3').text("Семинар успешно добавлен!");
			$('.success').animate({top:"0"}, 500);
		},
		error: function() {
			$('.error').animate({top:"0"}, 500);
		}
		});
		return false;
	});
	$('#adduser').submit(function(){
		$.ajax({
		type: "POST",
		url: "adduser.php",
		data: $("#adduser").serialize(),
		success: function(answ){
			if (answ=="") 
			{
				parent.$.fancybox.close();
				$('.success h3').text("Пользователь успешно добавлен!");
				$('.success').animate({top:"0"}, 500);
			} else
			{
				$("#errors").html(answ);
			}
		},
		error: function() {
			$('.error').animate({top:"0"}, 500);
		}
		});
		return false;
	});
	
  </script>
</body>
</html>



