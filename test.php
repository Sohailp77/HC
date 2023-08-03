<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

Require "fpdf186/fpdf.php";

include "./connect.php";



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$From=$_POST['from_x'];
$To=$_POST['to_x'];
$Adv_id=$_POST['adv_id'];
$Adv="";

// Retrieve data from database
$sql = "SELECT * FROM periphery.adv_attend WHERE adv_code=:adv_id AND appdate >= :fromDate AND appdate <= :toDate AND display='Y'";
$sql = $pgconn->prepare($sql);
$sql->bindParam(':adv_id', $Adv_id);
$sql->bindParam(':fromDate', $From);
$sql->bindParam(':toDate', $To);
$sql->execute();
$rows = $sql->fetchAll();
foreach ($rows as $row) {
    $Adv=$row['adv'];
}

class PDF extends FPDF {
    // Add a heading method to render the heading
    function Heading($text) {
        $this->SetFont('Arial', 'B', 14); // Set font style and size
        $this->Cell(0, 10, $text, 0, 1, 'C'); // Render the heading centered
    }
    function Subheading($text) {
        $this->SetFont('Arial', 'B', 12); // Set font style and size for the subheading
        $this->Cell(0, 10, $text, 0, 1, 'C');
    }
    function Subheading2($text) {
        $this->SetFont('Arial', 'B', 10); // Set font style and size for the subheading
        $this->Cell(0, 10, $text, 0, 1, 'C');
    }
    function CenterParagraph($text, $width) {
        $this->SetFont('Arial', '', 12); // Set font style and size for the paragraph
        $this->SetFillColor(255, 255, 255); // Set the background color to white (optional)
        $this->SetXY((210 - $width) / 2, $this->GetY()); // Center the paragraph horizontally
        $this->MultiCell($width, 6, $text, 0, 'C', true); // Render the paragraph with center alignment
    }
    function CenterLine($width) {
        $x1 = (210 - $width) / 2; // Center the line horizontally
        $y1 = $this->GetY();
        $x2 = $x1 + $width;
        $y2 = $y1;
        $this->Line($x1, $y1, $x2, $y2);
    }
}
}


//Create PDF instance
$pdf = new PDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'HIGH COURT OF BOMBAY AT GOA', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'APPEARANCE CERTIFICATE', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 10, '(issued on request)', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'TO WHOMSOEVER IT MAY CONCERN', 0, 1, 'C');

$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 10);
$pdf->CenterParagraph('Advocate General: ' . $Adv . ' has put in appearance in the proceedings/matters before this court as per particulars given below:', 180);
$pdf->Ln(5);
$pdf->CenterLine(190);
$pdf->Ln(5);

$pdf->SetFont('Arial','B',10);
$pdf->cell(20,6,'Sr No.',0,0,'C');
$pdf->cell(40,6,'Case No.',0,0,'C');
$pdf->cell(40,6,'Appearance Date',0,0,'C');
$pdf->cell(118,6,'Party Names',0,0,'C');
$pdf->Ln(5);

$pdf->Ln(5);
$pdf->CenterLine(190);
$pdf->Ln(5);

$serial=1;
$pdf->SetFont('Arial','',10);
foreach ($rows as $row) {
    $pdf->SetFont('Arial','',10);
    $pdf->cell(20,6,$serial,0,0,'C');
    $pdf->cell(40,6,$row['ci_no'],0,0,'C');
    $pdf->cell(40,6,$row['appdate'],0,0,'C');
    $pdf->SetFont('Arial','',9.5);
    $pdf->MultiCell(80,6,$row['petn']."\nVersus\n".$row['resp'],0,0,'L');
    // $pdf->MultiCell(80, 6, $row['petn'], 0, 'L');
    // $pdf->MultiCell(80, 6, 'Versus', 0, 'C');
    // $pdf->MultiCell(80, 6, $row['resp'], 0, 'R');
    $pdf->Ln(8);
    $serial++;
}
// Output the PDF
if ($rows) {
    $pdf->Output("output3.pdf", "F");
    $pdfUrl = "./output3.pdf";
    echo '<a href="' . $pdfUrl . '">Open PDF</a>';
} else {
    echo "No data was found";
}
?>
