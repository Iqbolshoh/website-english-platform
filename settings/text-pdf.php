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
$pdf->Cell(0, 10, 'Texts and Translations', 0, 1, 'C', true);
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 12);

$columnWidth = 190;

$rows = $query->select('texts', 'title, content, translation', "WHERE user_id = $user_id ORDER BY title ASC");

foreach ($rows as $row) {

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell($columnWidth, 10, $row['title'], 0, 1, 'C');

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell($columnWidth, 10, $row['content']);

    $pdf->SetTextColor(44, 62, 80);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell($columnWidth, 10, 'Translation', 0, 1, 'C');

    $pdf->SetTextColor(44, 62, 80);
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell($columnWidth, 10, $row['translation']);

    $pdf->Ln(10);
}

$pdf->Output($_SESSION['username'] . '-texts-' . date("H.i.s-m.d.Y") . '.pdf', 'D');
