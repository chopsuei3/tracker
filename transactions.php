<?php
function hijackTest()
{
	if(!isset($_SESSION['IPaddress']) || !isset($_SESSION['userAgent']))
		return false;

	if ($_SESSION['IPaddress'] != $_SERVER['REMOTE_ADDR'])
		return false;

	if( $_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT'])
		return false;

	return true;
}

function sessionStart()
{
	session_start();

	if(!hijackTest())
	{
		$_SESSION = array();
		$_SESSION['IPaddress'] = $_SERVER['REMOTE_ADDR'];
		$_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
		session_write_close();
	}
}

sessionStart();

if (!$_SESSION["valid_user"])
{
Header("Location: login.php");
}

require_once("dropdowna.php");
require_once("dropdown.php");
include ("dbconfig.php");

if($_POST["op"] == "edit")
{
	$updtranssql="UPDATE transactions SET transaction_date = '" . mysql_real_escape_string($_POST[updtransdate]) . "', strain_id = '" . mysql_real_escape_string($_POST[updtransstrain]) . "', transaction_amount = '" . mysql_real_escape_string($_POST[updtransprice]) . "', transaction_weight = '" . mysql_real_escape_string($_POST[updtransweight]) . "' WHERE transaction_id = '" . mysql_real_escape_string($_POST[updtrans]) . "'";

	if (!mysql_query($updtranssql))
	{
	echo "<h4>Database error: " . mysql_error() . "</h4>";
	exit;
	}  
	echo "1 transaction edited";
}

if($_POST["op"] == "add")
{
	$sql="INSERT INTO transactions (transaction_date, id, strain_id, transaction_amount, transaction_weight) VALUES ('" . mysql_real_escape_string($_POST[transdate]) . "','" . mysql_real_escape_string($_SESSION[valid_id]) . "','" . mysql_real_escape_string($_POST[transstrain]) . "','" . mysql_real_escape_string($_POST[transprice]) . "','" . mysql_real_escape_string($_POST[transweight]) . "')";

	if (!mysql_query($sql))
  	{
	echo "<h4>Database error: " . mysql_error() . "</h4>";
	exit;
	}
	echo "1 transaction added";
}

if($_POST["op"] == "delete")
{
	$deltranssql="DELETE FROM transactions WHERE transaction_id = '" . mysql_real_escape_string($_POST[deltrans]) . "'";

	if (!mysql_query($deltranssql))
	{
	echo "<h4>Database error: " . mysql_error() . "</h4>";
	exit;
	}
	echo "1 transaction deleted";
}

$uid = "select transactions.transaction_id, transactions.transaction_date, strains.strain_name, transactions.transaction_amount, transactions.transaction_weight from transactions, strains where (transactions.strain_id = strains.strain_id) and id = '".$_SESSION["valid_id"]."' ";

$result=mysql_query($uid);

if (!$result) 
{
    echo "<h4>Database error: " . mysql_error() . "</h4>";
    exit;
}

?>

<html>
<head>
  
<title>the chronicler | Transactions</title>

</head>
<body>

<h1>Transactions</h1>


<?php

if (mysql_num_rows($result) != 0) 
{
echo "<table><tr><th>Transaction ID</th><th>Transaction date</font></th><th>Strain name</th><th>Amount(grams)</th><th>$</th></tr>";

$i=0;
while ($i < mysql_num_rows($result)) {

$transaction_id=mysql_result($result,$i,"transaction_id");
$transaction_date=mysql_result($result,$i,"transaction_date");
$strain_name=mysql_result($result,$i,"strain_name");
$transaction_weight=mysql_result($result,$i,"transaction_weight");
$transaction_amount=mysql_result($result,$i,"transaction_amount");
?>

<tr>
<td align="center"><?php echo $transaction_id; ?></td>
<td align="center"><?php echo $transaction_date; ?></td>
<td align="center"><?php echo $strain_name; ?></td>
<td align="center"><?php echo $transaction_weight; ?></td>
<td align="center"><?php echo $transaction_amount; ?></td>
</tr>

<?php
$i++;
}
echo "</table><br />";
}
else
{
echo "<h3>There are no transactions added yet</h3>";
}
?>
<h2>Add Transaction</h2>
	<form action="transactions.php" method="post">
	<h6>Select strain</h6><?php dropdown(strain_id, strain_name, strains, strain_name, transstrain); ?>
	<input type="date" placeholder="Select date" name="transdate">
	<input type="number" placeholder="Weight (g)" name="transweight">
	<input type="number" placeholder="Price" name="transprice">
	<button type="submit" name="op" value="add">Add transaction</button>
	</form>

<h2>Edit Transaction</h2>
	<form action="transactions.php" method="post">
	<h6>Select transaction to edit</h6>
	<?php dropdowna(transaction_id, transaction_id, transactions, transaction_id, updtrans, $_SESSION["valid_id"]);?>
	<h5>New transaction details</h5>
	<h6>New strain name</h6>
	<?php dropdown(strain_id, strain_name, strains, strain_name, updtransstrain); ?>
	<input type="date" placeholder="New date" name="updtransdate">
	<input type="text" placeholder="New wt (g)" name="updtransweight">
	<input type="text" placeholder="New $" name="updtransprice">
	<button type="submit" name="op" value="edit">Edit transaction</button>
	</form>

<h2>Delete Transaction</h2>
	<form action="transactions.php" method="post">
	<h6>Select transaction to delete</h6>
	<?php dropdowna(transaction_id, transaction_id, transactions, transaction_id, deltrans, $_SESSION["valid_id"]);?>
	<button type="submit" name="op" value="delete">Delete transaction</button>
	</form>

<h2>Quick-add strain</h2>
	<h6>if the strain name is not listed, you can add the strain below</h6>
	<form action="strains.php" method="post">
	<input type="text" placeholder="new strain name" name="strain_name">
	<button type="submit" name="op" value="add">Add strain</button>
	</form>

<?php
mysql_close();
?>

<h3><a href="profile.php">Profile</a><br /></h3>
<h3><a href="strains.php">Strains</a><br /></h3>
<h3><a href="logout.php">Logout</a></h3>

</body>
</html>
