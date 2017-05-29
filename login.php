<?php
  include("settings.php");
  
  function generateCode($length=6) { 
     $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789"; 
     $code = ""; 
     $clen = strlen($chars) - 1;   
     while (strlen($code) < $length) { 
         $code .= $chars[mt_rand(0,$clen)];   
     } 
     return $code; 
   } 
   
   if(isset($_POST['submit'])) 
   { 
    $data = mysql_fetch_assoc(mysql_query("SELECT id, password FROM `users` WHERE `login`='".mysql_real_escape_string($_POST['login'])."' LIMIT 1")); 
      
    if($data['password'] === md5(md5($_POST['password']))) 
     { 
      $hash = md5(generateCode(10)); 

      mysql_query("UPDATE users SET hash='".$hash."' WHERE id='".$data['id']."'") or die("MySQL Error: " . mysql_error()); 

		setcookie("id", $data['id'], time()+60*60*24*30); 
		setcookie("hash", $hash, time()+60*60*24*30); 
		header("Refresh: 5;  url=index.php");
		echo "Авторизация прошла умпешно. Вы будете перенаправлены на главную страницу через 5 секунд... ";
		exit;
     } 
     else 
     { 
       echo "Вы ввели неправильный логин/пароль<br>"; 
     } 
   } 
   echo '<form method="POST"> 
   Логин <input name="login" type="text"><br> 
   Пароль <input name="password" type="password"><br> 
   <input name="submit" type="submit" value="Войти"> 
   </form>'
?>