<!doctype html>
<html>
  <head>
    <title>Добавить семинар</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<style>

	</style>  
<link rel="stylesheet" href="jquery-ui.css" type="text/css" media="screen" />
</head>

<body>	
<script src="jquery.js"></script>
<script src="jquery-ui.js"></script>
<script src="jquery.ui.datepicker-ru.js"></script>


  	<form id="add" method="POST" action="">
		<h1>Добавить семинар</h1>
		<input type="text" name="topic" placeholder="Тема" required="">
		<input id="datepicker" type="text" name="date" placeholder="Дата" required="" readonly="readonly">
		<input type="text" name="time" placeholder="Время начала" required="">
		<input type="text" name="maxusers" placeholder="Количество участников" required="">		
		<textarea rows="4" cols="40" name="description" placeholder="Описание"></textarea>		
		<button type="submit" name="submit">Добавить семинар</button>	
	</form>
	<script>
	$(document).ready(function() {
			$( "#datepicker" ).datepicker( {minDate: '0'});
			$( "#datepicker" ).datepicker( $.datepicker.regional[ "ru" ] );
			$( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
	});
	$('#add').submit(function(){
		$.ajax({
		type: "POST",
		url: "addwebinar.php",
		data: $("#add").serialize(),
		success: function(){
		alert('fuck');
		}
		});
		return false;
	});
	</script>
