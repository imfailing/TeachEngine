<?php
include_once 'settings.php';  

if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) 
{    
     $userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE id = '".intval($_COOKIE['id'])."' LIMIT 1"));

     if(($userdata['hash'] !== $_COOKIE['hash']) or ($userdata['id'] !== $_COOKIE['id'])) 
     { 
        setcookie('id', '', time() - 60*24*30*12, '/'); 
        setcookie('hash', '', time() - 60*24*30*12, '/');
		header("Location: index.php");
		exit;
     }
     if(($userdata['hash'] === $_COOKIE['hash']) and ($userdata['id'] === $_COOKIE['id'])) 
     {
     }
	  
} 
else 
{ 
		header("Location:index.php?errors=1");
		exit;
}
?>
