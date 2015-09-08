<?php 
if(!isset($_COOKIE['uniqueID']))
{
    $expire=time()+60*60*24*30;//however long you want (current expires in 30 days)
    setcookie('uniqueID', uniqid(), $expire);
	header("Location: login.php");
	die();
}
#echo "</br>Unique ID: ";
#echo $_COOKIE['uniqueID'];
#echo "</br>";
?>
