<?php
include 'settings.php';  
     $errors = array(); 

    if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login'])) 
     { 
         $errors[] = "Логин может состоять только из букв английского алфавита и цифр"; 
     } 
      
     if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30) 
     { 
         $errors[] = "Логин должен быть не меньше 3-х символов и не больше 30"; 
     } 
	 
     if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == FALSE) 
     { 
         $errors[] = "Неправильно введен e-mail"; 
     } 	 
      
   $query = mysql_query("SELECT COUNT(id) FROM users WHERE login='".mysql_real_escape_string($_POST['login'])."'")or die ("<br>Invalid query: " . mysql_error()); 
     if(mysql_result($query, 0) > 0) 
     { 
         $errors[] = "Пользователь с таким логином уже существует в базе данных"; 
     } 
	 
   $query = mysql_query("SELECT COUNT(id) FROM users WHERE login='".mysql_real_escape_string($_POST['email'])."'")or die ("<br>Invalid query: " . mysql_error()); 
     if(mysql_result($query, 0) > 0) 
     { 
         $errors[] = "Пользователь с таким e-mail уже существует в базе данных"; 
     } 	 
   
    if(count($errors) == 0) 
     { 
          
        $login = $_POST['login']; 
        $password = md5(md5(trim($_POST['password']))); 
		$email = $_POST['email'];
 		$name = $_POST['name'];
 		$type = $_POST['type'];
        mysql_query("INSERT INTO users SET login='".$login."', password='".$password."', name='".$name."', email='".$email."', type='".$type."'"); 
     } else
	 {
       print "<b>При добавлении пользователя произошли следующие ошибки:</b><br>"; 
       foreach($errors AS $error) 
       { 
         print $error."<br>"; 
       }   
     }
   ?>