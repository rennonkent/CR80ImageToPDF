<?php

require_once '../../vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class generateQRCode
{
  protected $id_number;
  protected string $outputDir;
  protected string $url = 'https://pit.edu.ph/id_validation/';

  public function __construct($idNumber)
  {
    $this->id_number = $idNumber;
    $this->outputDir = __DIR__ . '/profile/' . $this->id_number;
  }

  public function generateQRCode($trackingCode)
  {
    $qrData = $this->url . $trackingCode;

    $builder = new Builder(
      writer: new PngWriter(),
      writerOptions: [],
      validateResult: false,
      data: $qrData,
      encoding: new Encoding('UTF-8'),
      errorCorrectionLevel: ErrorCorrectionLevel::Medium,
      size: 300,
      margin: 10,
      roundBlockSizeMode: RoundBlockSizeMode::Margin
    );

    $result = $builder->build();
    $file = $this->outputDir . '/' . $this->id_number . '_qr.png';

    try {
      $result->saveToFile($file);
      return true;
    } catch (Exception $e) {
      return false;
    }
  }
}