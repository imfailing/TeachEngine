<?php
	error_reporting(E_ALL); 
	ini_set('display_errors','On');
	$uploaddir = 'tasks/';
	$uploadfile = $uploaddir.basename($_FILES['file']['name']);
	move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile);
	echo $_FILES['file']['name'];
?>