<?php
require('./fpdf186/fpdf.php');

session_start();

$user_id = $_SESSION['user_id'];

include '../config.php';
$query = new Query();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFillColor(22, 160, 133);
$pdf->Cell(0, 10, 'Dictionary Entries', 0, 1, 'C', true);
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);

$headerColors = [
    'word' => ['text' => [255, 255, 255], 'fill' => [22, 160, 133]],
    'translation' => ['text' => [255, 255, 255], 'fill' => [44, 62, 80]],
    'definition' => ['text' => [255, 255, 255], 'fill' => [52, 73, 94]]
];

$columnWidths = [
    'no' => 10,
    'word' => 50,
    'translation' => 60,
    'definition' => 0
];

$pdf->SetFillColor($headerColors['word']['fill'][0], $headerColors['word']['fill'][1], $headerColors['word']['fill'][2]);
$pdf->SetTextColor($headerColors['word']['text'][0], $headerColors['word']['text'][1], $headerColors['word']['text'][2]);

$pdf->SetFillColor($headerColors['translation']['fill'][0], $headerColors['translation']['fill'][1], $headerColors['translation']['fill'][2]);
$pdf->SetTextColor($headerColors['translation']['text'][0], $headerColors['translation']['text'][1], $headerColors['translation']['text'][2]);

$pdf->SetFillColor($headerColors['definition']['fill'][0], $headerColors['definition']['fill'][1], $headerColors['definition']['fill'][2]);
$pdf->SetTextColor($headerColors['definition']['text'][0], $headerColors['definition']['text'][1], $headerColors['definition']['text'][2]);

$pdf->SetTextColor(0, 0, 0);


$rows = $query->select('words', 'word, translation, definition', "WHERE user_id = $user_id");
$rowNumber = 1;
foreach ($rows as $row) {
    $pdf->Cell($columnWidths['no'], 10, $rowNumber++, 0);

    $pdf->Cell($columnWidths['word'], 10, $row['word'], 0);

    $pdf->Cell($columnWidths['translation'], 10, $row['translation'], 0);

    $pdf->SetX($pdf->GetX());
    $pdf->MultiCell($columnWidths['definition'], 10, $row['definition'], 0, 'L');

    $pdf->Ln();
    $pdf->Cell(0, 5, '', 0, 1);
}

$pdf->Output($_SESSION['username'] . '-dictionary-' . date("H.i.s-m.d.Y") . '.pdf', 'D');
