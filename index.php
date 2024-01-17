<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require 'vendor/autoload.php';

include "./dbconn.php";

if (isset($_POST["submit"])) {
    $fullName = $_POST["fullName"];
    $email = $_POST["email"];

    if (!file_exists("Symposium Certificates")) {
        mkdir("Symposium Certificates");
    }

    $sql = "INSERT INTO `certificates`(`id`, `fullname`, `email`) 
            VALUES (NULL,'$fullName','$email')";

    $result = mysqli_query($connect, $sql);

    if ($result) {
        require_once 'vendor/autoload.php';

        class SymposiumCertificate extends TCPDF {
            private $symposiumTitle;

            public function setSymposiumTitle($title) {
                $this->symposiumTitle = $title;
            }

            public function Header() {
                $this->SetFont('helvetica', 'B', 14);
                $this->Cell(0, 10, $this->symposiumTitle, 0, 1, 'C');
            }

            public function Footer() {
                $this->SetY(-15);
                $this->SetFont('helvetica', 'I', 8);
                $this->Cell(0, 10, 'Sertipiko ng Kaganapan ng Symposium', 0, 0, 'C');

                $this->SetY(-15);
                $this->SetFont('times', 'I', 12);
                $this->Cell(0, 10, 'Signatura ng Guro', 0, 0, 'R');
                $this->SetLineWidth(0.5);
                $this->Line($this->GetX(), $this->GetY(), $this->GetX() - 50, $this->GetY());

                $imageX = $this->GetX() - 50;
                $imageY = $this->GetY() - 10;

                $imageY = $this->GetY() - 15;
                $this->Image('signiture.png', $imageX, $imageY, 40);

            }
        }

        $pdf = new SymposiumCertificate('L', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->AddPage();

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->SetFont('times', 'B', 24);

        $pdf->Image('ribbon.png', 267, 5, 15, 35, 'PNG');

        $pdf->Cell(0, 20, 'Sertipiko ng Partisipasyon', 0, 1, 'C');

        $pdf->SetFont('times', '', 16);

        $pdf->SetLineWidth(1);
        $pdf->SetDrawColor(0, 0, 0);
        
        $pdf->Rect(5, 5, 287, 200, 'D');

        $pdf->Cell(0, 10, '', 0, 1, 'C');

        $pdf->SetFont('times', 'BU', 50);

        $pdf->Cell(0, 20, $fullName, 0, 1, 'C');

        $pdf->Cell(0, 10, '', 0, 1, 'C');

        $pdf->SetFont('times', '', 16);

        $pdf->MultiCell(0, 10, "Bilang pagpaparangal sa aktibong partisipasyon ni [$fullName] sa Symposium ng Pangkat Villanueva & Co. noong ika-labing anim ng Enero 2024, iginawad sa kanya ang isang sertipiko ng pagdalo. Ang sertipikadong ito ay naglalaman ng opisyal na rekord ng kanyang pagtanggap, na nagpapatunay sa kanyang mahalagang papel sa nasabing aktibidad. Ito'y isang malinaw na indikasyon ng kanyang hangaring mapabuti ang sarili sa pamamagitan ng pagsanay at pakikipag-ugnayan sa mga kapwa propesyonal.", 0, 'L');

        $pdf->Cell(0, 10, '', 0, 1, 'C');

        $pdf->MultiCell(0, 10, 'Pinapaabot ng pangkat ang kanilang pasasalamat sa iyong pagdalo.', 0, 'L');

        $pdf->Cell(0, 10, '', 0, 1, 'C');
        $pdf->Cell(0, 10, '', 0, 1, 'C');

        $pdf->SetFont('times', 'I', 16);

        $pdf->Cell(0, 10, 'Lubos na gumagalang,', 0, 1, 'L');
        $pdf->Cell(0, 10, 'Argie P. Delgado', 0, 1, 'L');

        $outputPath = 'Symposium Certificates/' . $fullName . '_symposium_certificate.pdf';
        $pdf->Output(__DIR__ . '/' . $outputPath, 'F');

        // 2. Send the Email with Attachment
        $mail = new PHPMailer(true);

        try {
            // Debuggin State
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;

            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ezgamer489@gmail.com';
            $mail->Password   = 'gooulyzzhsdrmnqb';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('ezgamer489@gmail.com', 'Symposium Event');
            $mail->addAddress($email, $fullName);

            $mail->addAttachment($outputPath);

            $mail->isHTML(true);
            $mail->Subject = 'Symposium Certificate';
            $mail->Body    = 'Dear ' . $fullName . ',<br><br>Congratulations! You have successfully attended the Symposium. Please find your certificate attached.<br><br>Best regards,<br>Villuaneuva & Co';

            $mail->send();

            echo 'Email sent successfully!';
        } catch (Exception $e) {
            echo "Mailer Error: {$mail->ErrorInfo}";
        }

        header("location: ./index.php");
        exit();
    } else {
        echo "Failed to create a certificate for the user";
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
        <title>| Automated Certificates Generator</title>
        <script src="https://kit.fontawesome.com/20fbad04b0.js" crossorigin="anonymous"></script>
        <link href="./css/dashboard.css" rel="stylesheet">
    </head>
    <body id="page-top">
        <div id="wrapper">
            <?php
                include "./include/sidebar.php";
            ?>
            <div id="content-wrapper" class="d-flex flex-column">
                <div id="content">
                    <?php
                        include "./include/topbar.php";
                    ?>
                    <div class="container-fluid px-4">
                        <div class="card-body">
                            You can add a certicates and make it visible in the table. 
                            <button onclick="openModal()" type="button" style="float: right; color: white;" class="btn btn-info">+ Add Certificates</button>
                        </div>
                        <div class="card-body">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-table me-1"></i>
                                    List of the Certificates
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="datatablesSimple" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-center">#</th>
                                                    <th scope="col" class="text-center">Name</th>
                                                    <th scope="col" class="text-center">Email</th>
                                                    <th scope="col" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    include "./dbconn.php";
                                                    $sql = "SELECT * FROM `certificates`";
                                                    $result = mysqli_query($connect, $sql);

                                                    while ($row = mysqli_fetch_assoc($result)) {

                                                        ?>
                                                        <tr>
                                                        <td>
                                                            <?php echo $row['id'] ?></td>
                                                            <td><?php echo $row['fullname'] ?></td>
                                                            <td><?php echo $row['email'] ?></td>
                                                            <td>
                                                                <div class="d-flex" style="gap: 10px; justify-content: center;">
                                                                    <a href="./viewcertificates.php?id=<?php echo $row['id'] ?>" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="View">
                                                                        <i class="far fa-eye fs-5"></i>
                                                                    </a>
                                                                    <a href="./delete_row.php?id=<?php echo $row['id'] ?>" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete">
                                                                        <i class="far fa-trash-can fs-5"></i>
                                                                    </a>
                                                                    <script>
                                                                        $(function () {
                                                                            $('[data-toggle="tooltip"]').tooltip();
                                                                        });
                                                                    </script>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    include "./include/footer.php";
                ?>
            </div>
        </div>
        <!-- Modal View Insert-->
        <div id="modal" class="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <p class="text-muted">Complete the form below to add a new Certificates<p>
                        <h5 class="modal-title">Add New Certificates</h5>
                        <button onclick="closeModal()" class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="container d-grid">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="">
                                <div class="mb-2">
                                    <div class="form-label">Full Name:</div>
                                    <input type="text" class="form-control" name="fullName" required>
                                </div>

                                <div class="mb-3">
                                    <div class="form-label">Email:</div>
                                    <input type="text" class="form-control" name="email" required>
                                </div>

                                <div class="mb-3">
                                    <button type="reset" class="btn btn-danger" name="Reset">Reset</button>
                                    <button type="submit" class="btn btn-success" name="submit">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>  
            </div>
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