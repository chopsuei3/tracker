<?php
session_start();

include "dbconfig.php";


// MySQL connection debugging
/*
$connection_id = mysql_thread_id($ms);
echo "<h1>DEBUG :: MYSQL CONNECTION ID = " . $connection_id . "</h1>";
if(is_resource($ms) && get_resource_type($ms) === 'mysql link persistent')
{
    echo "<h1>MYSQL CONNECTION IS OPEN! =)</h1>";
}
else
{
    echo "<h1>MYSQL CONNECTION IS CLOSED</h1>";
}
*/


if ($_GET["op"] == "thanks")
{
echo "<h3>Thanks for registering! Please log in below</h3>";
}
elseif ($_GET["op"] == "incorrect")
{
echo "<h3>Sorry, could not log you in. Please enter the correct login credentials.</h3>";
}

if (($_POST["op"] == "login") && !empty($_POST["username"]) && !empty($_POST["password"])) 
{
	$q = "SELECT * FROM `dbusers` " . "WHERE `username`='" . $_POST["username"] . "' " . "AND `password`=md5('" . $_POST["password"] . "')" . "LIMIT 1";
	
	$r = @mysql_query($q);

	$obj = mysql_fetch_object($r);

	if ($obj)
	{
	$_SESSION["valid_id"] = $obj->id;
	$_SESSION["valid_user"] = $_POST["username"];
	$_SESSION["valid_time"] = time();
	session_write_close();

	Header("Location: profile.php");
#	Header("Location: login.php?op=incorrect");

	}
	else
	{
	Header("Location: login.php?op=incorrect");
	}
}

?>

<html>
<head>
<title>the chronicler | Login</title>

</head>
<body>
<form action=login.php method="POST">        
<h2>Please sign in</h2>
<input type="text" placeholder="Username" name="username" size="15" align="center" >
<input type="password" placeholder="Password" name="password" size="8" align="center" >
<button type="submit" name="op" value="login">Sign in</button>
</form>

<h3><a href="register.php">Register</a><br /></h3>
<h3><a href="index.html">Home</a></h3>
</body>
</html>
