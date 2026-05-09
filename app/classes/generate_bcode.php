<?php

require_once '../../vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

class BarcodeGenerator
{

  protected $id_number; 

  public function __construct($idNumber) 
  {
    $this->id_number = $idNumber;
  }

  public function generateBarcode()
  {
    try {
      $outputDir = __DIR__ . '/profile/' . $this->id_number;
      
      if (!file_exists($outputDir)) {
        throw new Exception("Barcode: generated_id path or folder does not exists!"); 
      }

      $generator = new BarcodeGeneratorPNG();
      $barcodeData = $generator->getBarcode('SC-'. $this->id_number, $generator::TYPE_CODE_128, 2, 80);

      $barcodeFile = $outputDir . '/' . $this->id_number . '_bcode.png';
      file_put_contents($barcodeFile, $barcodeData);

      return 1;

    } catch (Exception $e) {
      return 0;
    }
  }
}

