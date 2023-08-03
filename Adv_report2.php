<!DOCTYPE html>
<html lang="en">
<head>
<style>
    /* Inline styles for centering the link */
    .center {
      text-align: center;
    }

    /* Inline styles for the PDF link */
    .pdf-link {
      display: inline-block;
      padding: 10px 20px;
      background-color: #007bff;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
    }

    /* Inline styles for the hover effect */
    .pdf-link:hover {
      background-color: #0056b3;
    }

    .error-message {
            color: red;
            text-align: center;
            font-size: 38px;
        }
  </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
</head>
<body>
    
</body>
</html>

<?php
    // error_reporting(E_ALL);
    // ini_set('display_errors', 1);
include("../../cisfunctions.php");
include "./connect.php";
Require "mic.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $From = $_POST['from_x'];
    $To = $_POST['to_x'];
    $Adv_id= $_POST['adv_id'];

    $heading1="Sr No.";
    $heading2="Case No.";
    $heading3="Party Names";
    $heading4="Appearance Date";
}

$sql = "SELECT * FROM periphery.adv_govt_det WHERE advid=:adv_id AND display='Y'";
$sql = $pgconn->prepare($sql);
$sql->bindParam(':adv_id', $Adv_id);
$sql->execute();
$rows = $sql->fetchAll();
foreach ($rows as $row) {
    $title=$row['title'];
    $adv_id=$row['advid'];
}

// $sql = "SELECT * FROM periphery.adv_attend WHERE adv_code=:adv_id";
// $sql = $pgconn->prepare($sql);
// $sql->bindParam(':adv_id', $Adv_id);
// $sql->execute();
// $rows = $sql->fetchAll();
// foreach ($rows as $row) {
//     $case_type=$row['case_type'];
// }

//case type
if($title=='Advocate Genral'){
// Retrieve data from the database where case stage is 503
$sql = "SELECT * FROM periphery.adv_attend WHERE adv_code=:adv_id AND appdate >= :fromDate AND appdate <= :toDate AND display='Y' AND stage='503' AND stage !='595' AND stage!='522'  ORDER BY appdate ASC";
$sql = $pgconn->prepare($sql);
$sql->bindParam(':adv_id', $Adv_id);
$sql->bindParam(':fromDate', $From);
$sql->bindParam(':toDate', $To);
$sql->execute();
$rows = $sql->fetchAll();
foreach ($rows as $row) {
    $Adv=$row['adv'];
}

//fetching data where case stage is 522
$sql2 = "SELECT * FROM periphery.adv_attend WHERE adv_code=:adv_id AND appdate >= :fromDate AND appdate <= :toDate AND display='Y' AND stage='522' AND stage !='503' AND stage!='595'  ORDER BY appdate ASC";
$sql2 = $pgconn->prepare($sql2);
$sql2->bindParam(':adv_id', $Adv_id);
$sql2->bindParam(':fromDate', $From);
$sql2->bindParam(':toDate', $To);
$sql2->execute();
$rows2 = $sql2->fetchAll();

//fetching data where case stage is in 595
$sql3 = "SELECT * FROM periphery.adv_attend WHERE adv_code=:adv_id AND appdate >= :fromDate AND appdate <= :toDate AND display='Y' AND stage='595' AND stage !='522' AND stage!='503'  ORDER BY appdate ASC";
$sql3 = $pgconn->prepare($sql3);
$sql3->bindParam(':adv_id', $Adv_id);
$sql3->bindParam(':fromDate', $From);
$sql3->bindParam(':toDate', $To);
$sql3->execute();
$rows3 = $sql3->fetchAll();
// print_r($rows3);

//Create PDF and add data to the table
$pdf = new PDF_MC_Table();
$pdf->AddPage();
$fullDate = date('j/n/Y ');
$cellPadding = 2;
$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(0, 10, 'HIGH COURT OF BOMBAY AT GOA', 0, 1, 'C');
$pdf->CenterLine(90);
// $pdf->Ln(0.1);
$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(0, 10, 'APPEARANCE CERTIFICATE', 0, 1, 'C');
$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(0, 5, '(issued on request)', 0, 1, 'C');
$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(0, 6, 'No.HCB/GOA/CERT/          /'.date('Y'), 0, 1, 'R');
$pdf->Cell(0, 6, 'Date:'.$fullDate, 0, 1, 'R');
$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(0, 10, 'TO WHOMSOEVER IT MAY CONCERN', 0, 1, 'C');

$pdf->Ln(5);

$pdf->CenterLine(190);
$pdf->Ln(5);
$pdf->SetFont('Times', 'B', 12);
$pdf->CenterParagraph('Advocate General: ' . $Adv . ' has put in appearance in the proceedings/matters before this court as per particulars given below:', 180);
$pdf->Ln(5);
$pdf->CenterLine(190);
$pdf->Ln(5);
$pdf->SetFont('Times', 'B', 14);
if (!empty($rows)) {
    $pdf->Cell(0, 10, 'FOR ADMISSION - FRESH', 0, 1, 'C');
    $pdf->CenterLine(65);
    $pdf->Ln(5);
}

$pdf->SetFont('Times', '', 9);
$pdf->headers($heading1,$heading2,$heading3,$heading4);
$pdf->SetWidths(array(20, 40, 100,34));

$serial=1;

// Retrieve data from the database where case stage is 503
foreach ($rows as $row) {
    $p_cino=$row['ci_no'];
    $caseno=getCaseNo($p_cino);

    // Create an array with the data fields
    $pdf->SetFont('Times', '', 9);
    $data = array(
        $serial,
        $caseno,
        $row['petn']."\nVs\n".$row['resp'],
        $row['appdate']
    );
    $serial++;
    // $pdf->Ln(5);
    // Call the Row($data) function to add a row to the PDF table with the data from the current $row
    $pdf->Row($data);
}
$pdf->SetFont('Times', 'B', 14);
if (!empty($rows2)) {
    $pdf->Ln(1);
    $pdf->Cell(0, 10, 'FOR ORDERS', 0, 1, 'C');
    $pdf->CenterLine(35);
    $pdf->Ln(5);
}

//fetching data where case stage is 522
foreach ($rows2 as $row2) {
    $p_cino=$row2['ci_no'];
    $caseno=getCaseNo($p_cino);

    // Create an array with the data fields
    $pdf->SetFont('Times', '', 10);
    $data2 = array(
        $serial,
        $caseno,
        $row2['petn']."\nVs\n".$row2['resp'],
        $row2['appdate']
    );
    $serial++;
    $pdf->SetFont('Times', 'B', 14);
    // $pdf->Cell(0, 10, 'FOR ORDERS', 0, 1, 'C');
    $pdf->SetFont('Times', '', 9);
    $pdf->Row($data2);
    
}
$pdf->SetFont('Times', 'B', 14);
if (!empty($rows3)) {
    $pdf->Ln(1);
    $pdf->Cell(0, 10, 'FOR FINAL HEARING', 0, 1, 'C');
    $pdf->CenterLine(55);
    $pdf->Ln(5);
}
//fetching data where case stage is in 595
foreach ($rows3 as $row3) {
    $p_cino=$row3['ci_no'];
    $caseno=getCaseNo($p_cino);

    // Create an array with the data fields
    $pdf->SetFont('Times', '', 10);
    $data3 = array(
        $serial,
        $caseno,
        $row3['petn']."\nVs\n".$row3['resp'],
        $row3['appdate']
    );
    $serial++;
    // $pdf->SetFont('Times', 'B', 14);
    // $pdf->Cell(0, 10, 'FOR FINAL HEARING', 0, 1, 'C');
    $pdf->SetFont('Times', '', 9);
    $pdf->Row($data3);
   
}

$pdf->Ln(15);
$pdf->SetFont('Times', '', 12);
$pdf->foot1();

// $signatureAreaHeight = 30; 

// //Check if there is enough space on the current page for the signature area
// $pdf->CheckPageBreak2($signatureAreaHeight);

// // Signature area
// $pdf->SetFont('Times', 'B', 10);
// $pdf->Cell(0, 5, "(Assistant Registrar)\n", 0, 1, 'R');
// $pdf->Cell(0, 5, "High Court of Bombay at Goa\n", 0, 1, 'R');
// $pdf->Cell(0, 5, "Panaji-Goa", 0, 1, 'R');


// Save the PDF to a file

if (empty($rows) && empty($row2) && empty($row3)) {
    echo "<div class='error-message '>";
    echo "No data was found for this period of time";
    echo "</div>";
}

else{
$pdf->Output("output3.pdf", "F");
$pdfUrl = "./output3.pdf";
echo "<div class='center'>";
echo '<a href="' . $pdfUrl . '"class="pdf-link">Open PDF</a>';
echo "</div>";
}

}


//for non advocate genral advocates
else{

$pdf = new PDF_MC_Table();
$pdf->AddPage();


$sql = "SELECT * FROM periphery.adv_attend WHERE adv_code=:adv_id AND appdate >= :fromDate AND appdate <= :toDate AND display='Y' AND case_type='2' ORDER BY appdate ASC";
$sql = $pgconn->prepare($sql);
$sql->bindParam(':adv_id', $Adv_id);
$sql->bindParam(':fromDate', $From);
$sql->bindParam(':toDate', $To);
$sql->execute();
$lists = $sql->fetchAll();
foreach ($lists as $list) {
    $Adv=$list['adv'];
}

$sql = "SELECT * FROM periphery.adv_attend WHERE adv_code=:adv_id AND appdate >= :fromDate AND appdate <= :toDate AND display='Y' AND case_type='3' ORDER BY appdate ASC";
$sql = $pgconn->prepare($sql);
$sql->bindParam(':adv_id', $Adv_id);
$sql->bindParam(':fromDate', $From);
$sql->bindParam(':toDate', $To);
$sql->execute();
$lists2 = $sql->fetchAll();


$cellPadding = 2;
$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(0, 10, 'HIGH COURT OF BOMBAY AT GOA', 0, 1, 'C');
$pdf->CenterLine(90);

$fullDate = date('j/n/Y ');

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(0, 10, 'APPEARANCE CERTIFICATE', 0, 1, 'C');
$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(0, 5, '(issued on request)', 0, 1, 'C');
$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(0, 6, 'No.HCB/GOA/CERT/          /'.date('Y'), 0, 1, 'R');
$pdf->Cell(0, 6, 'Date:'.$fullDate, 0, 1, 'R');
$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(0, 10, 'TO WHOMSOEVER IT MAY CONCERN', 0, 1, 'C');

$pdf->Ln(5);

$pdf->CenterLine(190);
$pdf->Ln(5);
$pdf->SetFont('Times', 'B', 12);
$pdf->CenterParagraph('Advocate : ' . $Adv . ' has put in appearance in the proceedings/matters before this court as per particulars given below:', 180);
$pdf->Ln(5);
$pdf->CenterLine(190);
$pdf->Ln(5);

$pdf->SetFont('Times', 'B', 14);
if (!empty($lists)) {
    $pdf->Cell(0, 10, 'CIVIL', 0, 1, 'C');
    $pdf->CenterLine(20);
    $pdf->Ln(5);
}

$pdf->SetFont('Times', '', 9);
$pdf->headers($heading1,$heading2,$heading3,$heading4);
$pdf->SetWidths(array(20, 40, 100,34));

$serial=1;

// Retrieve data from the database where case stage is 503
foreach ($lists as $list) {
    $p_cino=$list['ci_no'];
    $caseno=getCaseNo($p_cino);

    // Create an array with the data fields
    $pdf->SetFont('Times', '', 9);
    $data = array(
        $serial,
        $caseno,
        $list['petn']."\nVs\n".$list['resp'],
        $list['appdate']
    );
    $serial++;
    // $pdf->Ln(5);
    // Call the Row($data) function to add a row to the PDF table with the data from the current $row
    $pdf->Row($data);
}

$pdf->Ln(5);
$pdf->SetFont('Times', 'B', 14);
if (!empty($lists2)) {
    $pdf->Cell(0, 10, 'CRIMINAL', 0, 1, 'C');
    $pdf->CenterLine(30);
    $pdf->Ln(5);
}

$pdf->SetFont('Times', '', 9);

foreach ($lists2 as $list2) {
    $p_cino=$list2['ci_no'];
    $caseno=getCaseNo($p_cino);

    // Create an array with the data fields
    $pdf->SetFont('Times', '', 9);
    $data = array(
        $serial,
        $caseno,
        $list2['petn']."\nVs\n".$list2['resp'],
        $list2['appdate']
    );
    $serial++;
    // $pdf->Ln(5);
    // Call the Row($data) function to add a row to the PDF table with the data from the current $row
    $pdf->Row($data);
    $pdf->Ln(20);
    
}
$pdf->Ln(10);
$pdf->SetFont('Times', 'B', 12);
$pdf->foot2();
if($lists){
$pdf->Output("output.pdf", "F");
$pdfUrl = "./output.pdf";
echo "<div class='center'>";
echo '<a href="' . $pdfUrl . '"class="pdf-link">Open PDF</a>';
echo "</div>";
}
else{
echo "<div class='error-message '>";
echo "No data was found for this period of time";
echo "</div>";
}

}
?>
