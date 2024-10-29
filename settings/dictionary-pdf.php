<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}

include '../config.php';
$query = new Query();

require('./fpdf186/fpdf.php');
$user_id = $_SESSION['user_id'];

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFillColor(22, 160, 133);
$pdf->Cell(0, 10, 'Dictionary Entries', 0, 1, 'C', true);
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 12);

$headerColors = [
    'word' => ['text' => [255, 255, 255], 'fill' => [22, 160, 133]],
    'translation' => ['text' => [255, 255, 255], 'fill' => [44, 62, 80]]
];

$columnWidths = [
    'no' => 10,
    'word' => 90,
    'translation' => 90
];

$pdf->SetFillColor($headerColors['word']['fill'][0], $headerColors['word']['fill'][1], $headerColors['word']['fill'][2]);
$pdf->SetTextColor($headerColors['word']['text'][0], $headerColors['word']['text'][1], $headerColors['word']['text'][2]);
$pdf->Cell($columnWidths['no'], 10, 'No.', 1, 0, 'C', true);
$pdf->Cell($columnWidths['word'], 10, 'Word', 1, 0, 'C', true);
$pdf->Cell($columnWidths['translation'], 10, 'Translation', 1, 0, 'C', true);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0);

$rows = $query->select('words', 'word, translation', "WHERE user_id = $user_id ORDER BY word ASC");
$rowNumber = 1;
foreach ($rows as $row) {
    $pdf->Cell($columnWidths['no'], 10, $rowNumber++, 1);
    $pdf->Cell($columnWidths['word'], 10, $row['word'], 1);

    $pdf->Cell($columnWidths['translation'], 10, $row['translation'], 1);

    $pdf->Ln();
    $currentY = $pdf->GetY();
    $pdf->SetY($currentY);
}

$pdf->Output($_SESSION['username'] . '-dictionary-' . date("H.i.s-m.d.Y") . '.pdf', 'D');
