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

error_reporting(E_ALL);
ini_set('display_errors', 1);

include "./connect.php";
Require "mic.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $From = $_POST['from_x'];
    $To = $_POST['to_x'];
    $Adv_typ = $_POST['advtype'];
}


// Retrieve data from the database
$sql = "SELECT * FROM periphery.adv_attend WHERE adv_type=:adv_typ AND appdate >= :fromDate AND appdate <= :toDate AND display='Y'";
$sql = $pgconn->prepare($sql);
$sql->bindParam(':adv_typ', $Adv_typ);
$sql->bindParam(':fromDate', $From);
$sql->bindParam(':toDate', $To);
$sql->execute();
$rows = $sql->fetchAll();
// print_r($rows);

//Create PDF and add data to the table
$pdf = new PDF_MC_Table();
$pdf->AddPage();
$pdf->SetFont('Times', '', 14);

$pdf->Ln(5);
$pdf->SetWidths(array(80));
$hed=array("From, \nAssistant Registrar, \nHigh Court of Bombay at Goa, \nPanaji - Goa.");
$pdf->Row2($hed);
$pdf->Ln(10);

if ($Adv_typ == 'Govt Adv(State)') {
    $dest = array("To, \nThe Learned Advocate General, \nOffice of the Advocate General, \nPanaji - Goa.");
    $dest2=array(" \n \nSir, \n \tI am to enclose herein Appearance Certificate of the following Law Officers for the period mentioned against each, as desired. You are required to verify the same at your end");
} elseif ($Adv_typ == 3) {
    $dest = array("To \nThe Assistant Solicitor General of India, \nPanaji - Goa.");
} elseif ($Adv_typ == 'Govt Adv(Central)') {
    $dest = array("To \nThe Public Prosecutor, \nPanaji - Goa.");
    $dest2=array(" \n \nSir, \n \t \tI am to enclose herein Appearance Certificate of the following Law Officers for the period mentioned against each, as desired. You are required to verify the same at your end");
} else {
    // Handle other cases or set a default value for $dest if necessary
    $dest = array('', '');
}

$pdf->Row2($dest);
$pdf->Ln(6);
$pdf->SetWidths(array(190));
$pdf->Row2($dest2);
$pdf->Ln(6);

$pdf->CenterLine(190);
$pdf->Ln(2); 
$heading1="Sr No.";
$heading2="Name of the Learned Counsel";
$heading3="Appearance Certificate for";
$pdf->Cell(30, 10, "Sr No.", 0, 0, 'C');
$pdf->Cell(90, 10, "Name of the Learned Counsel", 0, 0, 'C');
$pdf->Cell(70, 10, "Appearance Certificate For", 0, 0, 'C');
$pdf->Ln(12);
$pdf->CenterLine(190);
$pdf->Ln(2); // Increase the line spacing for better readability

$pdf->SetWidths(array(30, 90, 70)); // Set the widths for the table cells

$uniqueRows = array();

foreach ($rows as $row) {
    // Check if the 'adv' value is already in the uniqueRows array
    if (!isset($uniqueRows[$row['adv']])) {
        // If 'adv' value is not in the array, add it to the array and create an entry for the current row
        $uniqueRows[$row['adv']] = array(
            'adv' => $row['adv'],
            'ci_no' => $row['ci_no'],
            'appdate' => $row['appdate']
        );
    }
}

$serial = 1;
foreach ($uniqueRows as $row) {
    // Create an array with the desired data fields
    $tmonth=$row['appdate'];
    $dateString = $row['appdate'];
    $tmon= explode("-", $dateString);

    $year=$tmon[0];

    
    if ($tmon[1]=="01")
		$tmonth="January";
	elseif($tmon[1]=="02")
		$tmonth="February";
	elseif($tmon[1]=="03")
		$tmonth="March";
	elseif($tmon[1]=="04")
		$tmonth="April";
	elseif($tmon[1]=="05")
		$tmonth="May";
	elseif($tmon[1]=="06")
		$tmonth="June";
	elseif($tmon[1]=="07")
		$tmonth="July";
	elseif($tmon[1]=="08")
		$tmonth="August";
	elseif($tmon[1]=="09")
		$tmonth="September";
	elseif($tmon[1]=="10")
		$tmonth="October";
	elseif($tmon[1]=="11")
		$tmonth="November";
	elseif($tmon[1]=="12")
		$tmonth="December";

    $data2 = array(
        $serial,
        $row['adv'],
        $tmonth." ". $year
    );

    $serial++;
    // Call the Row($data) function to add a row to the PDF table with the data from the current $row
    $pdf->Row3($data2);
    $pdf->Ln(5);
}

$pdf->SetFont('Times', '', 12);
$pdf->Ln(12);
$pdf->CenterLine(190);
$pdf->foot1();

// Saving the PDF to a file
if($rows){
$pdf->Output("output2.pdf", "F");
$pdfUrl = "./output2.pdf";
echo "<div class='center'>";
echo '<a href="' . $pdfUrl . '"class="pdf-link">Open PDF</a>';
echo "</div>";
}
else{
echo "<div class='error-message '>";
echo "No data was found for this period of time";
echo "</div>";
}


?>