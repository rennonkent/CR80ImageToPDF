<?php
  session_start();

  require_once('generate.php');
  require_once('generate_bcode.php');
  require_once('generate_qrcode.php');
  require_once('FetchEmployeeData.php');

  $wkhtmltoimage = "C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltoimage.exe";

  // Input data
  // $firstname = $_POST['fname'] ?? 'NoFirstname'; 
  // $lastname = $_POST['lname'] ?? 'NoLastname';
  $position = $_POST['position'] ?? 'NoPosition';
  $id_number = $_POST['id_number'] ?? '00-0000-000';
  $notify_name = $_POST['notify_name'] ?? 'none';
  $notify_contact = $_POST['notify_contact'] ?? 'none';
  $tracking_number = bin2hex(random_bytes(6));

  if ($id_number == '00-0000-000' || $id_number == '') {
    $_SESSION['response'] = 'ID Number is required';
    header('Location: index.php');
    exit;
  }

  /** Fetch Employee Data */
  $empData = new FetchEmployeeData($id_number);
  $empDataInfo = $empData->fetchEmployeeInfo();

  $firstname = $empDataInfo['fname'];
  $minitial = $empDataInfo['minitial'];
  $lastname = $empDataInfo['lname'];

  // Prepare output directory
  //$outputDir = __DIR__ . "\\generated_id\\" . $id_number;
  $outputDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'generated_id' . DIRECTORY_SEPARATOR . $id_number;
  if (!is_dir($outputDir)) mkdir($outputDir, 0777, true);

  // Helper function to generate image
  function generateImage($wkhtmltoimage, $htmlFile, $outputFile, $replacements = []) {
    $html = file_get_contents($htmlFile);
    foreach ($replacements as $key => $value) {
      $html = str_replace($key, htmlspecialchars($value), $html);
    }
    //$tempHtml = __DIR__ . "\\temp_" . uniqid() . ".html";
    $tempHtml = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'temp_' . uniqid() . '.html';
    file_put_contents($tempHtml, $html);
    $cmd = "\"$wkhtmltoimage\" --enable-local-file-access --width 353 --height 560 --disable-smart-width --format jpg --quality 100 \"$tempHtml\" \"$outputFile\" 2>&1";
    exec($cmd, $output, $returnCode);
    unlink($tempHtml);
    return [$returnCode, $output];
  }
  /** Generate Barcode */
  $bcode = new BarcodeGenerator($id_number);
  $bcodeStatus = $bcode->generateBarcode();

  /** Generate QR Code */
  $generateQRCode = new generateQRCode($id_number);
  $qrCodeStatus = $generateQRCode->generateQRCode($tracking_number);

  if ($bcodeStatus === 0 && $qrCodeStatus === false) {
    $_SESSION['response'] = 'Failed to generate barcode';
    header('Location: index.php');
    exit;
  } else {
    // Generate Front ID
    $outputImage = $outputDir . "\\" . $id_number . "-front.jpg";
    list($returnCode, $output) = generateImage($wkhtmltoimage, dirname(__DIR__, 2) . "\\templates\\index.html", $outputImage, [
      '{{FIRST_NAME}}' => strtoupper($firstname) .' '. strtoupper($minitial),
      '{{LAST_NAME}}'  => strtoupper($lastname),
      '{{POSITION}}'    => $position,
      '{{ID_NUMBER}}'  => 'SC - '. $id_number,
      '{{PROFILE_PIC_URL}}' => "file:///" . str_replace("\\", "/", dirname(__DIR__, 2)) . "/profile/$id_number/$id_number.png",
      '{{PROFILE_QR}}' => "file:///" . str_replace("\\", "/", dirname(__DIR__, 2)) . "/profile/$id_number/{$id_number}_qr.png"
    ]);

    // Generate Back ID
    $outputBackImage = $outputDir . "\\" . $id_number . "-back.jpg";
    list($returnCodeBack, $outputBack) = generateImage($wkhtmltoimage, dirname(__DIR__, 2) . "\\templates\\index_back.html", $outputBackImage, [
      '{{NOTIFY_NAME}}' => $notify_name,
      '{{NOTIFY_CONTACT}}' => $notify_contact,
      '{{ISSUED_DATE}}' => date('F d, Y'),
      '{{BARCODE}}' => "file:///" . str_replace("\\", "/", dirname(__DIR__, 2)) . "/profile/$id_number/{$id_number}_bcode.png",
      '{{SIGNATURE}}' => "file:///" . str_replace("\\", "/", dirname(__DIR__, 2)) . "/profile/$id_number/{$id_number}_signature.png",
    ]);
  }

  // Output results
  if ($returnCode === 0 && $returnCodeBack === 0) {
  //if ($returnCode === 0) {
    $imageToPDF = new ImageToPDF($id_number);
    $imageToPDF->generatePDF();

    $_SESSION['response'] = "<br> Tracking Number: ". $tracking_number;;
    header('Location: ../../index.php');
    exit;
    
  } else {
    echo "Error generating image(s):<br>";

    echo nl2br(htmlspecialchars(implode("\n", array_merge($output, $outputBack))));
  }

?>



