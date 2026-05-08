<?php

session_start();

require __DIR__ . '/vendor/autoload.php';

class ImageToPDF
{
  protected $cardWidth = 53.98;
  protected $cardHeight = 85.60;
  protected $imagePath = __DIR__ . '/generated_id/';

  public $idNumber;

  public function __construct($idNumber)
  {
    $this->idNumber = $idNumber;
  }

  private function setImageToPage($pdf, $pageLocation)
  {
    if(!file_exists($this->imagePath . $this->idNumber . '-' . $pageLocation . '.png')) {
      throw new Exception("generated_id path or folder does not exist!"); 
    }

    $pdf->Image(
        $this->imagePath. $this->idNumber . '-' . $pageLocation . '.png',
        0,
        0,
        $this->cardWidth,
        $this->cardHeight,
        'PNG'
    );
  }

  public function generatePDF()
  {
    $response_msg = '';
    try {

      if (!file_exists(__DIR__ . '/pdf')) {
        mkdir(__DIR__ . '/pdf');
      }

      if(!file_exists($this->imagePath)) {
        throw new Exception("generated_id path or folder does not exist!"); 
      }

      $pdf = new TCPDF('P', 'mm', [$this->cardHeight, $this->cardWidth], true, 'UTF-8', false);
      $pdf->SetMargins(0, 0, 0);
      $pdf->SetAutoPageBreak(false, 0);
      $pdf->setPrintHeader(false);
      $pdf->setPrintFooter(false);

      // Create front page
      $pdf->AddPage();
      $this->setImageToPage($pdf, 'front');

      // Create back page
      $pdf->AddPage();
      $this->setImageToPage($pdf, 'back');

      $pdf->Output(__DIR__ . '/pdf/' . $this->idNumber . '-cr80-card.pdf', 'F');

      $_SESSION['response'] = "Success:PDF generated successfully.";
      header('Location: index.php');
      exit;

    } catch (Exception $e) {
      $_SESSION['response'] = "Error: " . $e->getMessage();
      header('Location: index.php');
      exit;
    } 
    
  }
}

$idNumber = $_POST['idNumber'];

if ($idNumber == '') {
  $_SESSION['response'] = 'ID Number is required';
  header('Location: index.php');
  exit;
}

$imageToPDF = new ImageToPDF($idNumber);
$imageToPDF->generatePDF();