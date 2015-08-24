<?php
include ("dbconfig.php");

if ( $_GET["op"] == "register" )
{

if (empty($_POST["username"]))
{
die( "Problem with your registration info. "
."Please go back and try again.");
Header("Location: register.php");
}

$username = test_input($_POST["username"]);
if (!preg_match("/^[a-zA-Z0-9]*$/",$username)) {
die( "Only letters and numbers allowed in username");
}

$password = test_input($_POST["password"]);
if (!preg_match("/^[a-zA-Z0-9]*$/",$password)) {
die( "Only letters and numbers allowed in password");
}

$email = test_input($_POST["email"]);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
die( "Invalid email format");
}

$q = "INSERT INTO `dbusers` (`username`,`password`,`email`) "
."VALUES ('" . mysql_real_escape_string($username) . "', "
."md5('" . mysql_real_escape_string($password) . "'), "
."'" . mysql_real_escape_string($email) . "')";

$r = mysql_query($q);

if ( !mysql_insert_id() )
  {
die("Error: User not added to database.");
  }
else
  {
Header("Location: register.php?op=thanks");
  }
}
elseif ( $_GET["op"] == "thanks" )
{
echo "<h2>thanks for registering!</h2>";
}


?>

<html>
<head>
<title>the chronicler | registration</title>
<link href="css/bootstrap.css" rel="stylesheet" media="screen">
<style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading {      
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }
</style>

</head>
<body>
    
<div class="container">
<div class="row-fluid">
<div class="span4 offset4">
<form class="form-signin" action="register.php" method="POST">        
<h2 class="form-signin-heading">please register</h2>
<input type="text" class="input-block-level" placeholder="username" name="username" maxlength="16">
<input type="password" class="input-block-level" placeholder="password" name="password" maxlength="16">
<input type="text" class="input-block-level" placeholder="email address" name="email" maxlength="30">
<button class="btn btn-large btn-primary" type="submit" name="op" value="register">register</button>
</form>
<h3><a href="index.html">home</a><br /></h3>
<h3><a href="login.php">login</a></h3>
</div>
</div>
</div>

<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>