<?php 
include "./dbconn.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sqlDelete = "DELETE FROM certificates WHERE id = $id";

    $resultDelete = mysqli_query($connect, $sqlDelete);

    if ($resultDelete) {
        header("Location: ./certificates.php");
        exit();  // Make sure to exit after a header redirect
    } else {
        echo "Failed to delete a row in the database";
    }
} else {
    echo "Invalid ID";
}
?>
