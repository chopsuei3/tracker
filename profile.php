<?php
session_start();

if (!$_SESSION["valid_user"])
{
Header("Location: login.php");
}

include ("dbconfig.php");

//mysql_connect($host,$user,$pass);
//@mysql_select_db($db) or die( "Unable to select database");

$uid = "select transactions.transaction_date, strains.strain_name, transactions.transaction_amount, transactions.transaction_weight
from transactions, strains
where (transactions.strain_id = strains.strain_id) and id = '".$_SESSION["valid_id"]."' ";

$result=mysql_query($uid);

if (!$result) {
//    echo "Could not successfully run query ($sql) from database: " . mysql_error();
    exit;
}

?>

<html>
<head>
<title>the chronicler | User Profile</title>

</head>
<body>
       
<h1>User Profile</h1>
<?php 
echo "<h4>Username: ".$_SESSION[valid_user]."</h4>";
echo "<h4>User ID: ".$_SESSION[valid_id]."</h4>";

if (mysql_num_rows($result) != 0) 
{
	echo "<table><tr><th>Transaction Date</th><th>Strain Name</th><th>Transaction Weight (grams)</th><th>Transaction Amount ($)</th></tr>";
	
	$i=0;
	while ($i < mysql_num_rows($result)) 
	{
	$transaction_date=mysql_result($result,$i,"transaction_date");
	$strain_name=mysql_result($result,$i,"strain_name");
	$transaction_weight=mysql_result($result,$i,"transaction_weight");
	$transaction_amount=mysql_result($result,$i,"transaction_amount");
	}
?>

<tr>
<td><?php echo $transaction_date; ?></td>
<td><?php echo $strain_name; ?></td>
<td><?php echo $transaction_weight; ?></td>
<td><?php echo $transaction_amount; ?></td>
</tr>

<?php
$i++;
echo "</table><br />";
mysql_close();

}
else
{
echo "<h3>You haven't added any transactions yet.</h3>";
}

?>
<h3><a href="profile.php">Profile</a></h3>
<h3><a href="strains.php">Strains</a></h3>
<h3><a href="transactions.php">Transactions</a></h3>
<h3><a href="logout.php">Logout</a></h3>
</body>
</html>
