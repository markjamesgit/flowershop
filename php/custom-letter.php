<?php
require_once('tcpdf/tcpdf.php'); // adjust path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $letterContent = $_POST['letter_content'] ?? '';

    // Create new PDF document
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Sunny Blooms');
    $pdf->SetTitle('Digital Letter');
    $pdf->SetMargins(20, 20, 20);
    $pdf->SetAutoPageBreak(TRUE, 20);
    $pdf->AddPage();
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetFont('helvetica', '', 12);

    // Write HTML content
    $pdf->writeHTML($letterContent, true, false, true, false, '');

    // Output PDF
    $pdf->Output('digital_letter.pdf', 'D'); // D = Download
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Custom Letter</title>
  <script src="https://cdn.ckeditor.com/4.21.0/standard-all/ckeditor.js"></script>
</head>
<body>
  <h1>Create Your Digital Letter</h1>

  <form method="post" action="custom-letter.php">
    <textarea name="letter_content" id="letter_content"></textarea><br><br>
    <button type="submit">Download as PDF</button>
  </form>

  <hr>

  <h2>Live Preview</h2>
  <div id="preview" style="border:1px solid #ccc; padding:10px; min-height:100px;"></div>

  <script>
    CKEDITOR.replace('letter_content', {
      extraPlugins: 'emoji',
      removePlugins: 'image,about'
    });

    setInterval(() => {
      const html = CKEDITOR.instances.letter_content.getData();
      document.getElementById('preview').innerHTML = html;
    }, 1000);
  </script>
</body>
</html>