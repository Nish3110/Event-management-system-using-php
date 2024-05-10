<?php

session_start();
require('fpdf.php');

// Capture the form data or set default values
$userName = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : 'No Email';
$address = isset($_POST['address_line']) ? $_POST['address_line'] : 'No Address'; // Note: address input name is misspelled as 'adress'
$address2 = isset($_POST['address_line2']) ? $_POST['address_line2'] : ''; // Optional
$packagename = isset($_POST['eventName']) ? $_POST['eventName'] : ''; // Optional
$package_price = isset($_POST['eventPrice']) ? $_POST['eventPrice'] : ''; // Optional


// Initialize PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'IDYLLIC EVENT CHECKOUT SUMMARY', 0, 1, 'C');
$pdf->Ln(10);  

// User Details
$pdf->SetFont('Arial', 'B', 12); 
$pdf->Cell(0, 10, 'User Details', 0, 1);
$pdf->SetFont('Arial', '', 12); 
$pdf->Cell(0, 10, 'Name: ' . $name, 0, 1);
$pdf->Cell(0, 10, 'Email: ' . $email, 0, 1);
$pdf->Cell(0, 10, 'Address: ' . $address . ' ' . $address2, 0, 1);
$pdf->Ln(5);

// Event Details (example with static values, replace with dynamic values as necessary)
$pdf->SetFont('Arial', 'B', 12); 
$pdf->Cell(0, 10, 'Event Details', 0, 1);
$header = array('Event Name', 'Price');
foreach($header as $col) {
    $pdf->Cell(90, 7, $col, 1);
}
$pdf->Ln();

// Example Data - replace with dynamic event data as necessary
$data = array(
    array($packagename,$package_price), 
);

$total = 0;
$pdf->SetFont('Arial', '', 12); 
foreach($data as $row) {
    foreach($row as $col) {
        $pdf->Cell(90, 6, $col, 1);
    }
    $total += (float)end($row); // Calculate total based on last element (price)
    $pdf->Ln();
}

$tax = $total * 0.15; // 15% tax
$grandTotal = $total + $tax;

$pdf->Ln(5); 
$pdf->SetFont('Arial', 'B', 12); 
$pdf->Cell(90, 7, 'Total', 1);
$pdf->Cell(90, 7, '$'.number_format($total, 2), 1, 0, 'R');
$pdf->Ln();

$pdf->Cell(90, 7, 'Tax (15%)', 1);
$pdf->Cell(90, 7, '$'.number_format($tax, 2), 1, 0, 'R');
$pdf->Ln();

$pdf->SetFont('Arial', 'B', 12); 
$pdf->Cell(90, 7, 'Grand Total', 1);
$pdf->Cell(90, 7, '$'.number_format($grandTotal, 2), 1, 0, 'R');

// Finalize PDF Output
$pdf->Output('I', 'event_checkout_summary.pdf');

?>