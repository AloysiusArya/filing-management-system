<?php
require_once 'vendor/autoload.php';

use setasign\Fpdi\Fpdi;

$targetDir = "uploads/";

if (isset($_FILES['signature']['tmp_name']) && isset($_POST['pdfFileName'])) {
    $pdfFileName = $_POST['pdfFileName'];
    $signaturePath = $targetDir . uniqid() . '_' . basename($_FILES['signature']['name']);

    if (move_uploaded_file($_FILES['signature']['tmp_name'], $signaturePath)) {
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile('file/' . $pdfFileName);
        $tplId = $pdf->importPage(1);
        $pdf->addPage();
        $size = $pdf->getTemplateSize($tplId);
        $pdf->useTemplate($tplId, 0, 0, $size['width'], $size['height'], TRUE);

        // Sesuaikan posisi dan ukuran tanda tangan di sini
        // Untuk membuatnya lebih kecil dan lebih ke atas, kurangi nilai width dan atur posisi y lebih kecil
        $signatureWidth = 20; // Lebar tanda tangan, kurangi untuk membuat lebih kecil
        $positionX = $pdf->GetPageWidth() - $signatureWidth - 5; // Posisi X, geser ke kiri dengan mengurangi nilai
        $positionY = $pdf->GetPageHeight() - 20; // Posisi Y, geser ke atas dengan mengurangi nilai
        $pdf->Image($signaturePath, $positionX, $positionY, $signatureWidth);

        $outputPath = 'file/' . $pdfFileName;
        $pdf->Output('F', $outputPath);

        echo json_encode(["status" => "success", "message" => "PDF updated with signature.", "path" => $outputPath]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to upload signature."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Signature or PDF file name not provided."]);
}
