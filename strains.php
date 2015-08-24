<?php
session_start();

if (!$_SESSION["valid_user"])
{
// User not logged in, redirect to login page
Header("Location: login.php");
}

require_once("dropdown.php");
include ("dbconfig.php");

// Set up database connection
$con = mysql_connect($host,$user,$pass);
@mysql_select_db($db) or die( "unable to select database");

if ($_POST["op"] == "edit")
{
$updstrainsql="UPDATE strains SET strain_name = '" . mysql_real_escape_string($_POST[upd_strain_name]) . "' WHERE strain_id = '" . mysql_real_escape_string($_POST[updstrain]) . "'";
    if (!mysql_query($updstrainsql,$con))
    {
    die('Error: ' . mysql_error());
    }
echo "1 record updated";
}

if ($_POST["op"] == "delete")
{
$delstrainsql="DELETE FROM strains WHERE strain_id = '" . mysql_real_escape_string($_POST[delstrain]) . "'";
$delstrtransql="DELETE FROM transactions where strain_id = '" . mysql_real_escape_string($_POST[delstrain]) . "'";
    if (!mysql_query($delstrainsql,$con))
    {
    die('Error: ' . mysql_error());
    }
    if (!mysql_query($delstrtransql,$con))
    {
    die('error: ' . mysql_error());
    }
echo "1 strain removed";
}

if ( $_POST["op"] == "add" )
{
$addStrain = "INSERT INTO strains (strain_name) VALUES ('" . mysql_real_escape_string($_POST[strain_name]) . "')";
    if (!mysql_query($addStrain,$con))
      {
            if (mysql_errno() == 1062)
            {
            echo "<p>error: strain already in the database</p><br />";
            }
            else
            {
              die('error: ' . mysql_error()); 
            }
        }
echo "1 strain added";
}

$strainlist = "SELECT strain_name from strains";
$result = mysql_query($strainlist,$con);
    if (!$result) 
    {
        echo "could not successfully run query ($strainlist) from database: " . mysql_error();
        exit;
    }
    if (mysql_num_rows($result) == 0) 
    {
        echo "no transactions found";
    }
?>

<html>
<head>
<title>the chronicler | strains</title>
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">


<style type="text/css">
.content .span4 {
    margin-left: 0;
    padding-left: 19px;
    border-left: 1px solid #eee;
}
</style>


</head>
<body>
    <div class="container">
<h1>strains list</h1>  
<table class="table">
<?php
$i=0;
while ($i < mysql_num_rows($result)) {

$strain_name=mysql_result($result,$i,"strain_name");
?>
<tr>
<td><? echo $strain_name; ?></td>
</tr>
<?php
$i++;
}
echo "</table><br />";
?>

<div class="row-fluid">
    <div class="span4">
<h2>search strains</h2>
<form class="form-search" action="search.php" method="post">
    <input type="text" class="input-medium" placeholder="enter strain name" name="term">
    <button class="btn btn-small btn-primary" type="submit" name="op" value="strain">Search</button>
</form>
    </div>
    <div class="span4">
<h2>add strain</h2>
<form class="form-inline" action="strains.php" method="post">
     <input class="input-medium" type="text" placeholder="enter strain name" name="strain_name" maxlength="255">
     <button class="btn btn-small btn-primary" type="submit" name="op" value="add">Add</button>
</form>
    </div>
    <div class="span4">
<h2>delete strain</h2>
<form class="form-inline" action="strains.php" method="post" >
    <?php dropdown(strain_id, strain_name, strains, strain_name, delstrain); ?>
    <button class="btn btn-small btn-primary" type="submit" name="op" value="delete">Delete</button>
</form>
    </div>
</div>

<div class="row-fluid">
    <div class="span8 offset1">
<h2>edit strain name</h2>
<form class="form-horizontal" action="strains.php" method="post">
    <?php dropdown(strain_id, strain_name, strains, strain_name, updstrain); ?> 
    <input class="input-medium" type="text" placeholder="enter new strain name" name="upd_strain_name" maxlength="255">
    <button class="btn btn-small btn-primary" type="submit" name="op" value="edit">Edit</button>
</form>


<?php
mysql_close($con);
?>
<div class="span4 offset4">
<h3><a href="profile.php">profile</a><br /></h3>
<h3><a href="transactions.php">transactions</a><br /></h3>
<h3><a href="logout.php">logout</a></h3>
</div>
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>