<?php include '../check.php';

require('./fpdf186/fpdf.php');
$user_id = $_SESSION['user_id'];

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFillColor(22, 160, 133);
$pdf->Cell(0, 10, 'Sentences and Translations', 0, 1, 'C', true);
$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 12);

$columnWidths = [
    'sentence' => 95,
    'translation' => 95
];

$headerColors = [
    'sentence' => ['text' => [255, 255, 255], 'fill' => [44, 62, 80]],
    'translation' => ['text' => [255, 255, 255], 'fill' => [22, 160, 133]]
];

$pdf->SetFillColor($headerColors['sentence']['fill'][0], $headerColors['sentence']['fill'][1], $headerColors['sentence']['fill'][2]);
$pdf->SetTextColor($headerColors['sentence']['text'][0], $headerColors['sentence']['text'][1], $headerColors['sentence']['text'][2]);
$pdf->Cell($columnWidths['sentence'], 10, 'Sentence', 1, 0, 'C', true);

$pdf->SetFillColor($headerColors['translation']['fill'][0], $headerColors['translation']['fill'][1], $headerColors['translation']['fill'][2]);
$pdf->SetTextColor($headerColors['translation']['text'][0], $headerColors['translation']['text'][1], $headerColors['translation']['text'][2]);
$pdf->Cell($columnWidths['translation'], 10, 'Translation', 1, 1, 'C', true);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(5);

$rows = $query->select('sentences', 'sentence, translation', "WHERE user_id = $user_id ORDER BY sentence ASC");
foreach ($rows as $row) {
    $y = $pdf->GetY();
    $pageHeight = $pdf->GetPageHeight();
    $rowHeight = 5;
    if ($y + $rowHeight > $pageHeight - 20) {
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);
        $y = $pdf->GetY();
    }

    $pdf->SetTextColor(44, 62, 80);
    $pdf->SetX($pdf->GetX());
    $pdf->MultiCell($columnWidths['sentence'], $rowHeight, $row['sentence'], 0, 'L');

    $pdf->SetTextColor(22, 160, 133);
    $pdf->SetXY($pdf->GetX() + $columnWidths['sentence'], $y);
    $pdf->MultiCell($columnWidths['translation'], $rowHeight, $row['translation'], 0, 'L');

    $pdf->Ln();
}

$pdf->Output($_SESSION['username'] . '-sentences-' . date("H.i.s-m.d.Y") . '.pdf', 'D');
