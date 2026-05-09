<?php

require '../../vendor/autoload.php';

class ImageToPDF
{
  protected $cardWidth = 53.98;
  protected $cardHeight = 85.60;
  public $imagePath;

  public $idNumber;

  public function __construct($idNumber)
  {
    $this->idNumber = $idNumber;
    $this->imagePath = dirname(__DIR__, 2) . '/generated_id/';
  }

  private function setImageToPage($pdf, $pageLocation)
  {
    if(!file_exists($this->imagePath . $this->idNumber . '/'. $this->idNumber . '-' . $pageLocation . '.JPG')) {
      throw new Exception("PDF: generated_id path or folder does not exists!"); 
    }
    
    $pdf->Image(
        $this->imagePath . $this->idNumber .'/'. $this->idNumber .'-'. $pageLocation . '.JPG',
        0,
        0,
        $this->cardWidth,
        $this->cardHeight,
        'JPG'
    );
  }

  public function generatePDF()
  {
    $response_msg = '';
    try {

      if (!file_exists(dirname(__DIR__, 2) . '/pdf')) {
        mkdir(dirname(__DIR__, 2) . '/pdf');
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

      //$pdf->Output(dirname(__DIR__, 2) . '/pdf/' . $this->idNumber . '-cr80-card.pdf', 'F');
      $pdf->Output($this->idNumber .'cr80-card.pdf', 'D');

      // $_SESSION['response'] = "Success:PDF generated successfully.";
      // header('Location: index.php');
      // exit;

    } catch (Exception $e) {
      $_SESSION['response'] = "Error: " . $e->getMessage();
      header('Location: ../../index.php');
      exit;
    } 
    
  }
}

$id_number = $_GET['id_number'];

if ($id_number == '00-0000-000') {
  $_SESSION['response'] = 'ID Number is required';
  header('Location: ../../index.php');
  exit;
}

$imageToPDF = new ImageToPDF($id_number);
$imageToPDF->generatePDF();