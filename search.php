<html>
<title>the chronicler | search</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
		<body>
		<div class="container">
		<div class="row-fluid">
		<div class="span8 offset2">
		<h3>search results for </h3>
<?php

include "dbconfig.php";

$con = mysql_connect($host,$user,$pass);
@mysql_select_db($db) or die( "unable to select database");
 

if($_POST["op"] == "strain")
{
	$term = $_POST['term'];
	if(empty($term))
	{
	die("Your search term was blank");
	}

echo ""."'".$term."'";
// Create query
$strainsearch = "SELECT strain_name FROM strains where strain_name like '%" . mysql_real_escape_string($_POST["term"]) . "%'";

$result=mysql_query($strainsearch,$con);

if (mysql_num_rows($result) == 0) 
{
echo "<p>no results found</p><br />";
}
else 
{

echo "<br />";
echo "<div class=\"span4 offset4\">";
echo "<table class=\"table\">";

$i=0;
while ($i < mysql_num_rows($result))  
{
$strainresults=mysql_result($result,$i,"strain_name");
echo "<tr><td>" . $strainresults . "</td></tr>";
$i++;
}
echo "</table><br />";
}
echo "</div></div>";
}

?>

<br />
<div class="span4 offset4">
<h3><a href="profile.php">profile</a><br /></h3>
<h3><a href="strains.php">strains</a><br /></h3>
<h3><a href="transactions.php">transactions</a><br /></h3>
<h3><a href="logout.php">logout</a></h3>

</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>

</html>