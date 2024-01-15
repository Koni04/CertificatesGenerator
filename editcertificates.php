<?php

include "./dbconn.php";

$id = $_GET['id'];

if(isset($_POST['submit'])) {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];

    $sql = "UPDATE `certificates` 
    SET `fullname`='$fullName',`email`='$email' WHERE id = $id";

    $result = mysqli_query($connect, $sql);
    if($result) {
        header("Location: ./certificates.php");
    } else {
        echo "Error updating record: " . mysqli_error($connect);
    }
} 

?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Control Panel">
        <meta name="author" content="Argie Delgado">
        <title>| Automated Certificates Generator | Update Certificates</title>
        <!-- Awesome Fonts -->
        <script src="https://kit.fontawesome.com/20fbad04b0.js" crossorigin="anonymous"></script>
        <link href="./css/dashboard.css" rel="stylesheet">
    </head>
    <body id="page-top">
        <!-- Page Wrapper -->
        <div id="wrapper">
            <?php
                include "./include/sidebar.php";
            ?>
            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
                <!-- Main Content -->
                <div id="content">
                    <?php
                        include "./include/topbar.php";
                    ?>
                    <!-- Begin Page Content -->
                    <div class="container-fluid px-4">
                        <?php
                            include "./dbconn.php";

                            $id = $_GET['id'];
                            $sql = "SELECT * FROM `certificates` WHERE id = $id LIMIT 1";

                            $result = mysqli_query($connect, $sql);
                            $row = mysqli_fetch_assoc($result);
                        ?>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="">
                                <div class="mb-2">
                                    <div class="form-label">Full Name:</div>
                                    <input type="text" class="form-control" name="fullName" value="<?php echo $row['fullname'] ?>" required>
                                </div>

                                <div class="mb-3">
                                    <div class="form-label">Email:</div>
                                    <input type="text" class="form-control" name="email" value="<?php echo $row['email'] ?>" required>
                                </div>

                                <div class="mb-3">
                                    <button type="reset" class="btn btn-danger" name="Reset">Reset</button>
                                    <button type="submit" class="btn btn-success" name="submit">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- End of Page Content -->
                </div>
                <!-- End of Main Content -->
                <?php
                    include "./include/footer.php";
                ?>
            </div>
            <!-- End of Content Wrapper -->
        </div>
        <!-- Bootstrap core JavaScript-->
        <script src="./js/dashboard.js"></script>
        <script src="./js/bootstrap.bundle.min.js"></script>
        <!-- Core plugin JavaScript-->
        <script src="./js/jquery.easing.min.js"></script>
        <!-- Custom scripts for all pages-->
        <script src="./js/sidebarfuntion.js"></script>
        <script src="./js/modalfunction.js"></script>
    </body>
</html> 