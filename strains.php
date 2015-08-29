<?php
session_start();

if (!$_SESSION["valid_user"])
{
Header("Location: login.php");
}

require_once("dropdown.php");
include ("dbconfig.php");

if ($_POST["op"] == "edit")
{
$updstrainsql="UPDATE strains SET strain_name = '" . mysql_real_escape_string($_POST[upd_strain_name]) . "' WHERE strain_id = '" . mysql_real_escape_string($_POST[updstrain]) . "'";
    if (!mysql_query($updstrainsql))
    {
	echo "<h4>Database error: " . mysql_error() . "</h4>";
	exit;
    }
echo "<h4>Strain updated</h4>";
}

if ($_POST["op"] == "delete")
{
$delstrainsql="DELETE FROM strains WHERE strain_id = '" . mysql_real_escape_string($_POST[delstrain]) . "'";
$delstrtransql="DELETE FROM transactions where strain_id = '" . mysql_real_escape_string($_POST[delstrain]) . "'";

	if (!mysql_query($delstrainsql))
	{
	echo "<h4>Database error: " . mysql_error() . "</h4>";
	exit;
	}	
	
	if (!mysql_query($delstrtransql))
	{
	echo "<h4>Database error: " . mysql_error() . "</h4>";
	exit;
	}
	echo "<h4>Strain removed</h4>";
}

if ( $_POST["op"] == "add" )
{
$addStrain = "INSERT INTO strains (strain_name) VALUES ('" . mysql_real_escape_string($_POST[strain_name]) . "')";
    if (!mysql_query($addStrain))
      {
            if (mysql_errno() == 1062)
            {
            echo "<h4>Database error: Strain already in the database</h4>";
			exit;
            }
            else
            {
			echo "<h4>Database error: " . mysql_error() . "</h4>";
			exit;
            }
        }
echo "<h4>Strain added</h4>";
}

$strainlist = "SELECT strain_name from strains";
$result = mysql_query($strainlist);
    if (!$result) 
    {
		echo "<h4>Database error: " . mysql_error() . "</h4>";
		exit;
    }
	
?>

<html>
<head>
<title>the chronicler | Strains</title>

</head>
<body>

<h1>Strains list</h1>  

<?php
$i=0;
if (mysql_num_rows($result) != 0) 
    {
	echo "<table>";	
	while ($i < mysql_num_rows($result)) 
	{
	$strain_name=mysql_result($result,$i,'strain_name');
	echo "<tr><td>" . $strain_name . "</td></tr>";
	$i++;
	}
	echo "</table><br />";
	}
?>

<h2>Search strains</h2>
<form action="search.php" method="post">
    <input type="text" placeholder="enter strain name" name="term">
    <button type="submit" name="op" value="strain">Search</button>
</form>

<h2>Add strain</h2>
<form action="strains.php" method="post">
     <input type="text" placeholder="enter strain name" name="strain_name" maxlength="255">
     <button type="submit" name="op" value="add">Add</button>
</form>

<h2>Delete strain</h2>
<form action="strains.php" method="post" >
    <?php dropdown(strain_id, strain_name, strains, strain_name, delstrain); ?>
    <button type="submit" name="op" value="delete">Delete</button>
</form>

<h2>Edit strain name</h2>
<form action="strains.php" method="post">
    <?php dropdown(strain_id, strain_name, strains, strain_name, updstrain); ?> 
    <input type="text" placeholder="enter new strain name" name="upd_strain_name" maxlength="255">
    <button type="submit" name="op" value="edit">Edit</button>
</form>


<?php
//mysql_close($con);
?>
<h3><a href="profile.php">Profile</a><br /></h3>
<h3><a href="transactions.php">Transactions</a><br /></h3>
<h3><a href="logout.php">Logout</a></h3>
</body>
</html>
