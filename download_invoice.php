<?php
require('fpdf186/fpdf.php'); // included FPDF library
include 'db.php';
session_start();

if (!isset($_GET['id'])) {
    die('No invoice ID specified');
}

// Fetch invoice details
$invoice_id = $_GET['id'];
$query = "SELECT i.*, b.start_date, b.end_date 
          FROM invoices i 
          LEFT JOIN booking b ON i.client_id = b.id 
          WHERE i.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $invoice_id);
$stmt->execute();
$result = $stmt->get_result();
$invoice = $result->fetch_assoc();

if (!$invoice) {
    die('Invoice not found');
}

class PDF extends FPDF {
    function Header() {
        // Logo
        $this->Image('images/logo.png', 10, 6, 30); // Adjust path to your logo
        // Company Details
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(80);
        $this->Cell(30, 10, 'Lifeline Care Center', 0, 0, 'C');
        $this->Ln(7);
        $this->SetFont('Arial', '', 10);
        $this->Cell(80);
        $this->Cell(30, 10, '123 Prince Street, Colombo 4', 0, 0, 'C');
        $this->Ln(7);
        $this->Cell(80);
        $this->Cell(30, 10, 'Tel: 0112585899', 0, 0, 'C');
        $this->Ln(20);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Create PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Invoice Title
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'INVOICE', 0, 1, 'C');
$pdf->Ln(10);

// Invoice Details
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Invoice Date:', 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 10, date('Y-m-d', strtotime($invoice['invoice_date'])), 0);
$pdf->Ln();

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Client Name:', 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 10, $invoice['full_name'], 0);
$pdf->Ln();

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Service Type:', 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 10, ucfirst(str_replace('_', ' ', $invoice['service_type'])), 0);
$pdf->Ln();

if (isset($invoice['start_date']) && isset($invoice['end_date'])) {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Service Period:', 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(100, 10, date('Y-m-d', strtotime($invoice['start_date'])) . ' to ' . date('Y-m-d', strtotime($invoice['end_date'])), 0);
    $pdf->Ln();
}

$pdf->Ln(10);

// Amount Details
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(135, 10, 'Total Amount:', 0, 0, 'R');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 10, 'Rs. ' . number_format($invoice['amount'], 2), 0, 0, 'R');
$pdf->Ln(20);

// Terms and Conditions
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Terms and Conditions:', 0, 1);
$pdf->SetFont('Arial', '', 10);
$pdf->MultiCell(0, 5, '1. Full payment is required before the service begins.
2. Cancellations must be made at least 48 hours in advance.
3. Service schedules may be adjusted with prior notice.
4. Additional charges may apply for extended hours.
5. This invoice is valid for 30 days from the issue date.');

$pdf->Ln(10);

// Thank You Note
$pdf->SetFont('Arial', 'I', 12);
$pdf->Cell(0, 10, 'Thank you for choosing Lifeline Care Center!', 0, 1, 'C');

// Output PDF
$pdf->Output('Invoice_' . $invoice_id . '.pdf', 'D');
?>