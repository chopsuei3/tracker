<?php
session_start();

if (!$_SESSION["valid_user"])
{

// User not logged in, redirect to login page
Header("Location: login.php");
}

require_once("dropdowna.php");
require_once("dropdown.php");
include ("dbconfig.php");

$con = mysql_connect($host,$user,$pass);
@mysql_select_db($db) or die( "unable to select database");

if($_POST["op"] == "edit")
{
$updtranssql="UPDATE transactions SET transaction_date = '" . mysql_real_escape_string($_POST[updtransdate]) . "', strain_id = '" . mysql_real_escape_string($_POST[updtransstrain]) . "', transaction_amount = '" . mysql_real_escape_string($_POST[updtransprice]) . "', transaction_weight = '" . mysql_real_escape_string($_POST[updtransweight]) . "' WHERE transaction_id = '" . mysql_real_escape_string($_POST[updtrans]) . "'";

if (!mysql_query($updtranssql,$con))
  {
  die('Error: ' . mysql_error());
  }  
echo "1 transaction edited";
}

if($_POST["op"] == "add")
{
$sql="INSERT INTO transactions (transaction_date, id, strain_id, transaction_amount, transaction_weight)
VALUES
('" . mysql_real_escape_string($_POST[transdate]) . "','" . mysql_real_escape_string($_SESSION[valid_id]) . "','" . mysql_real_escape_string($_POST[transstrain]) . "','" . mysql_real_escape_string($_POST[transprice]) . "','" . mysql_real_escape_string($_POST[transweight]) . "')";

if (!mysql_query($sql,$con))
  {
  die('error: ' . mysql_error());
  }
echo "1 transaction added";
}

if($_POST["op"] == "delete")
{
    $deltranssql="DELETE FROM transactions WHERE transaction_id = '" . mysql_real_escape_string($_POST[deltrans]) . "'";
if (!mysql_query($deltranssql,$con))
  {
  die('error: ' . mysql_error());
  }
echo "1 transaction deleted";
}

$uid = "select transactions.transaction_id, transactions.transaction_date, strains.strain_name, transactions.transaction_amount, transactions.transaction_weight
from transactions, strains
where (transactions.strain_id = strains.strain_id) and id = '".$_SESSION["valid_id"]."' ";

$result=mysql_query($uid,$con);

if (!$result) {
    echo "could not successfully run query ($sql) from database " . mysql_error();
    exit;
}

if (mysql_num_rows($result) == 0) {
    echo "no transactions found";
    echo "<br />";
}
?>

<html>
<head>

<script language="JavaScript" src="/tracker/js/CalendarPopup.js"></script>
	<script language="JavaScript">
	var cal = new CalendarPopup();
	</script>
    
<title>the chronicler | transactions</title>
<link href="css/bootstrap.css" rel="stylesheet" media="screen">

</head>
<body>
     <div class="container">

<h1>transactions</h1>
<table class="table">
<tr>
<th>transaction ID</th>
<th>transaction date</font></th>
<th>strain name</th>
<th>transaction weight (grams)</th>
<th>transaction amount ($)</th>

</tr>

<?php
$i=0;
while ($i < mysql_num_rows($result)) {

$transaction_id=mysql_result($result,$i,"transaction_id");
$transaction_date=mysql_result($result,$i,"transaction_date");
$strain_name=mysql_result($result,$i,"strain_name");
$transaction_weight=mysql_result($result,$i,"transaction_weight");
$transaction_amount=mysql_result($result,$i,"transaction_amount");
?>

<tr>
<td><?php echo $transaction_id; ?></td>
<td><?php echo $transaction_date; ?></td>
<td><?php echo $strain_name; ?></td>
<td><?php echo $transaction_weight; ?></td>
<td><?php echo $transaction_amount; ?></td>
</tr>

<?php
$i++;
}
echo "</table><br />";
?>
<div class="row-fluid">
<div class="span12">
<h2>add transaction</h2>
<form class="form-inline" action="transactions.php" method="post">
    <h6>select strain</h6><?php dropdown(strain_id, strain_name, strains, strain_name, transstrain); ?>
    <input class="input-small" type="date" placeholder="select date" name="transdate">
    <a href="#" onClick="cal.select(document.forms['addtrans'].transdate,'anchor1','yyyy/MM/dd'); return false;" name="anchor1" id="anchor1">select</a>
    <div class="input-append">
    <input class="input-small" type="number" placeholder="weight (g)" name="transweight">
    <span class="add-on">g</span>
    </div>
    <div class="input-prepend">
    <span class="add-on">$</span>
    <input class="input-small" type="number" placeholder="price" name="transprice">
    </div>
    <button class="btn btn-small btn-primary" type="submit" name="op" value="add">add transaction</button>
</form>
</div>

<div class="span12">
<h2>edit transaction</h2>
<form class="form-horizontal" action="transactions.php" method="post">
    <h6>select transaction to edit</h6>
    <?php dropdowna(transaction_id, transaction_id, transactions, transaction_id, updtrans, $_SESSION["valid_id"]);?>
    <h5>new transaction details</h5>
	<h6>new strain name</h6>
    <?php dropdown(strain_id, strain_name, strains, strain_name, updtransstrain); ?>
    <input class="input-small" type="date" placeholder="new date" name="updtransdate">
    <a href="#"
   onClick="cal.select(document.forms['edittransaction'].updtransdate,'anchor1','yyyy/MM/dd'); return false;"
   name="anchor1" id="anchor1">select</a>
    <input class="input-small" type="text" placeholder="new wt (g)" name="updtransweight">
    <input class="input-small" type="text" placeholder="new $" name="updtransprice">
    <button class="btn btn-small btn-primary" type="submit" name="op" value="edit">edit transaction</button>
</form>
</div>

    <div class="span6">
<h2>delete transaction</h2>
<form class="form-inline" action="transactions.php" method="post">
    <h6>select transaction to delete</h6>
    <?php dropdowna(transaction_id, transaction_id, transactions, transaction_id, deltrans, $_SESSION["valid_id"]);?>
    <button class="btn btn-small btn-primary" type="submit" name="op" value="delete">delete transaction</button>
</form>
    </div>
    <div class="span6">
<h2>quick-add strain</h2>
<h6>if the strain name is not listed, you can add the strain below</h6>
<form class="form-inline" action="strains.php" method="post">
         <input class="input-large" type="text" placeholder="new strain name" name="strain_name">
	 <button class="btn btn-small btn-primary" type="submit" name="op" value="add">add strain</button>
</form>
    </div>
</div>    
<?php
mysql_close($con);
?>
<div class="span4 offset4">
<h3><a href="profile.php">profile</a><br /></h3>
<h3><a href="strains.php">strains</a><br /></h3>
<h3><a href="logout.php">logout</a></h3>
</div>

<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
