<?php
include ("dbconfig.php");

function test_input($data) 
{
$data = trim($data);
$data = stripslashes($data);
$data = htmlspecialchars($data);
return $data;
}

if ( $_POST["op"] == "register" )
{

	if (empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["email"]))
	{
	die( "Problem with your registration info. Please try again.");
	Header("Location: register.php");
	}

	$username = test_input($_POST["username"]);
	if (!preg_match("/^[a-zA-Z0-9]*$/",$username)) 
	{
	die( "Only letters and numbers allowed in username");
	}

	$password = test_input($_POST["password"]);
	if (!preg_match("/^[a-zA-Z0-9]*$/",$password)) 
	{
	die( "Only letters and numbers allowed in password");
	}

	$email = test_input($_POST["email"]);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
	{
	die( "Invalid email format");
	}

	$q = "INSERT INTO `dbusers` (`username`,`password`,`email`) "."VALUES ('" . mysql_real_escape_string($username) . "', "."md5('" . mysql_real_escape_string($password) . "'), "."'" . mysql_real_escape_string($email) . "')";

	$r = mysql_query($q);

	if ( !mysql_insert_id() )
	{
	die("Database Error: User not added to database. Plese contact the webmaster.");
	}
	else
	{
	Header("Location: login.php?op=thanks");
	}
}

?>

<html>
<head>
<title>the chronicler | Registration</title>

</head>
<body>

<form action="register.php" method="POST">        
<h2>Please register</h2>
<input type="text" placeholder="Username" name="username" maxlength="16">
<input type="password" placeholder="Password" name="password" maxlength="16">
<input type="text" placeholder="Email address" name="email" maxlength="30">
<button type="submit" name="op" value="register">Register</button>
</form>
<h3><a href="index.html">Home</a><br /></h3>
<h3><a href="login.php">Login</a></h3>

</body>
</html>
