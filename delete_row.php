<?php 
include "./dbconn.php";

$id = $_GET['id'];

$sqlDelete = "DELETE FROM certificates WHERE id = $id";

$resultDelete = mysqli_query($connect, $sqlDelete);

if ($resultDelete) {
    header("Location: ./index.php");
    exit();
} else {
    echo "Failed to delete a row in the database";
}

?>