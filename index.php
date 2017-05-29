<?php
  include_once("settings.php");
  $error[0] = 'Неправильная пара логин/пароль. Пожалуйста введите верные логин и пароль.';
  $error[1] = 'Вы не авторизированы. Пожалуйста введите логин и пароль.';
  
	if(isset($_GET['errors']))
	{
		$errors=$_GET['errors'];
	}

  
  if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) 
{    
     $userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE id = '".intval($_COOKIE['id'])."' LIMIT 1"));

     if(($userdata['hash'] !== $_COOKIE['hash']) or ($userdata['id'] !== $_COOKIE['id'])) 
     { 
         setcookie('id', '', time() - 60*24*30*12, '/'); 
         setcookie('hash', '', time() - 60*24*30*12, '/');
     }
     if(($userdata['hash'] === $_COOKIE['hash']) and ($userdata['id'] === $_COOKIE['id'])) 
     {
		header("Location: sessions.php");
		exit;
     }
	  
	} 
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
		header("Location: sessions.php");
		exit;
     } 
     else 
     { 
       $errors=0; 
     } 
   } 
?>
<!DOCTYPE html>

<html>
<head>
<title>Авторизация</title>
	<link type="text/css" rel="stylesheet" href="index.css">	
</head>

<body>

<form id="login" method="POST" action="">
    <h1>Авторизация</h1>
	<?php if (isset($errors)) {print '<h4>'.$error[$errors].'</h4>';}?>
    <fieldset id="inputs">
        <input id="username" name="login" type="text" placeholder="Логин" autofocus required>   
        <input id="password" name="password" type="password" placeholder="Пароль" required>
    </fieldset>
    <fieldset id="actions">
        <input type="submit" name="submit" id="submit" value="Авторизироваться">
    </fieldset>
</form>


</body>
</html>
