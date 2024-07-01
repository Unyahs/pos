<?php 
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: ../index.php");
}

require("connection.php");
$id = $_GET['id'];
 

$sql = "DELETE FROM discounts WHERE discount_id='$id'";
$result = mysqli_query($conn, $sql);


if ($result) {
	echo "Deleted Successfully";
	header("Location: ../admin/discounts.php");
}
else{
	die(mysqli_error($conn));
}

	

exit;
?>




