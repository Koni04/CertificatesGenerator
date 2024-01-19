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
                $photoWidthBottomRight = 85;
                $photoHeightBottomRight = 85;
                $photoXBottomRight = $this->getPageWidth() - $photoWidthBottomRight - 10;
                $photoYBottomRight = $this->getPageHeight() - $photoHeightBottomRight - 5;
            
                $this->Image('designSaGedliRight.png', $photoXBottomRight, $photoYBottomRight, $photoWidthBottomRight, $photoHeightBottomRight);
            
                $photoWidthBottomLeft = 85;
                $photoHeightBottomLeft = 85;
                $photoXBottomLeft = 10;
                $photoYBottomLeft = $this->getPageHeight() - $photoHeightBottomLeft - 5;
            
                $this->Image('starSaGedliY.png', $photoXBottomLeft, $photoYBottomLeft, $photoWidthBottomLeft, $photoHeightBottomLeft);
            }
        }

        $pdf = new SymposiumCertificate('L', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetMargins(10, 10, 10);

        $pdf->AddPage();

        $pdf->SetFillColor(255, 248, 243);

        $pdf->Rect(0, 0, $pdf->getPageWidth(), $pdf->getPageHeight(), 'F');

        $pdf->SetAutoPageBreak(FALSE, PDF_MARGIN_BOTTOM);

        $pdf->Cell(0, 10, '', 0, 1, 'C');

        $pdf->SetFont('Alice', '', 13);

        $pdf->Cell(0, 15, 'IGINAWAD ITONG', 0, 1, 'C');

        $pdf->SetFont('lemon', '', 50);

        $pdf->Cell(0, 20, 'Sertipiko ng Pagdalo', 0, 1, 'C');

        $pdf->SetFont('Alice', '', 12);

        $pdf->Cell(0, 10, 'Kay', 0, 1, 'C');

        $pdf->Cell(0, 10, '', 0, 1, 'C');

        $pdf->SetFont('Angelina', '', 75);

        $pdf->Cell(0, 20, $fullName, 0, 1, 'C');

        $pdf->Cell(0, 10, '', 0, 1, 'C');

        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetFont('InstrumentSerif', 'I', 15);

        $pdf->SetXY(0, $pdf->getPageHeight() / 2);

        $pdf->MultiCell(0, 10, '
                Sa pagpapakita ng masigasig na pagdalo sa Symposium na pinamagatan
                Tuklasin ang Mundo ng AI (Artificial Intelligence). Iginawad ngayong Ika-18 ng Enero taong 2024.
                ', 0, 'C');

        $pdf->SetFont('Alice', 'I', 16);

        $signatureWidth = 24;
        $signatureHeight = 24;
        
        $signatureX = ($pdf->getPageWidth() - $signatureWidth) / 2;
        $signatureY = $pdf->getPageHeight() - 85;
        
        // Adjust the position for the image
        $imageOffset = 35;
        $imageY = $signatureY + $imageOffset;
        
        $pdf->Image('monedaSigniture.png', $signatureX, $imageY, $signatureWidth, $signatureHeight);
        
        $textY = $imageY + $signatureHeight - 10;
        $pdf->SetXY($signatureX, $textY);
        $pdf->SetFont('Alice', 'U', 12);
        $pdf->Cell($signatureWidth, 10, 'Angelica D. Moneda', 0, 1, 'C');
        
        $pdf->SetXY($signatureX, $textY + 5);
        $pdf->SetFont('Alice', '', 12);
        $pdf->Cell($signatureWidth, 10, 'Lider ng Grupo', 0, 1, 'C');

        $photoWidthTopLeft = 85;
        $photoHeightTopLeft = 85;
        $photoXTopLeft = 10;
        $photoYTopLeft = 5;

        $pdf->Image('designSaGedliLeft.png', $photoXTopLeft, $photoYTopLeft, $photoWidthTopLeft, $photoHeightTopLeft);

        $photoWidthTopRight = 85;
        $photoHeightTopRight = 85;
        $photoXTopRight = $pdf->GetPageWidth() - $photoWidthTopRight - 10;
        $photoYTopRight = 5;

        $pdf->Image('starSaGedliX.png', $photoXTopRight, $photoYTopRight, $photoWidthTopRight, $photoHeightTopRight);

        $outputPath = 'Symposium Certificates/' . $fullName . '_symposium_certificate.pdf';
        $pdf->Output(__DIR__ . '/' . $outputPath, 'F');

        // 2. Send the Email with Attachment
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;

            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'symposiumevent4@gmail.com';
            $mail->Password   = 'shjkgtsluwpfoxqz';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('thomasgarcia041206@gmail.com', 'Symposium Event');
            $mail->addAddress($email, $fullName);

            $mail->addAttachment($outputPath);

            $mail->isHTML(true);
            $mail->Subject = 'Symposium Certificate';
            $mail->Body    = 'Dear ' . $fullName . ',<br><br>Congratulations! You have successfully attended the Symposium. Please find your certificate attached.<br><br>Best regards,<br>Moneda Group';

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
                                                                    <a href="./delete_ro.php?id=<?php echo $row['id'] ?>" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete">
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