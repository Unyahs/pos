<?php 
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: ../index.php");
}

require("connection.php");
$id = $_GET['id'];
 

$sql = "DELETE FROM products WHERE product_id='$id'";
$result = mysqli_query($conn, $sql);


if ($result) {
	echo "Deleted Successfully";
	header("Location: ../staff/products.php");
}
else{
	die(mysqli_error($conn));
}

	

exit;
?>




