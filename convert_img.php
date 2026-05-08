<?php
  session_start();

  require_once('generate.php');

  $wkhtmltoimage = "C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltoimage.exe";

  // Input data
  $firstname = $_POST['fname'] ?? 'NoFirstname'; 
  $lastname = $_POST['lname'] ?? 'NoLastname';
  $position = $_POST['position'] ?? 'NoPosition';
  $id_number = $_POST['id_number'] ?? '00-0000-000';
  $notify_name = $_POST['notify_name'] ?? 'none';
  $notify_contact = $_POST['notify_contact'] ?? 'none';

  // if ($id_number === '00-0000-000') {
  //   throw new Exception('ID Number is not allowed.'); 
  // }

  if ($id_number == '00-0000-000' || $id_number == '') {
    $_SESSION['response'] = 'ID Number is required';
    header('Location: index.php');
    exit;
  }

  // Prepare output directory
  $outputDir = __DIR__ . "\\generated_id\\" . $id_number;
  if (!is_dir($outputDir)) mkdir($outputDir, 0777, true);

  // Helper function to generate image
  function generateImage($wkhtmltoimage, $htmlFile, $outputFile, $replacements = []) {
    $html = file_get_contents($htmlFile);
    foreach ($replacements as $key => $value) {
      $html = str_replace($key, htmlspecialchars($value), $html);
    }
    $tempHtml = __DIR__ . "\\temp_" . uniqid() . ".html";
    file_put_contents($tempHtml, $html);
    $cmd = "\"$wkhtmltoimage\" --enable-local-file-access --width 353 --height 560 --disable-smart-width --format jpg --quality 100 \"$tempHtml\" \"$outputFile\" 2>&1";
    exec($cmd, $output, $returnCode);
    unlink($tempHtml);
    return [$returnCode, $output];
  }

  // Generate Front ID
  $outputImage = $outputDir . "\\" . $id_number . "-front.jpg";
  list($returnCode, $output) = generateImage($wkhtmltoimage, __DIR__ . "\\templates\\index.html", $outputImage, [
    '{{FIRST_NAME}}' => strtoupper($firstname),
    '{{LAST_NAME}}'  => strtoupper($lastname),
    '{{POSITION}}'    => $position,
    '{{ID_NUMBER}}'  => 'SC - '. $id_number,
    '{{PROFILE_PIC_URL}}' => "file:///" . str_replace("\\", "/", __DIR__) . "/profile/$id_number/$id_number.png",
    '{{PROFILE_QR}}' => "file:///" . str_replace("\\", "/", __DIR__) . "/profile/$id_number/{$id_number}_qr.png"
  ]);

  // Generate Back ID
  $outputBackImage = $outputDir . "\\" . $id_number . "-back.jpg";
  list($returnCodeBack, $outputBack) = generateImage($wkhtmltoimage, __DIR__ . "\\templates\\index_back.html", $outputBackImage, [
    '{{NOTIFY_NAME}}' => $notify_name,
    '{{NOTIFY_CONTACT}}' => $notify_contact,
    '{{ISSUED_DATE}}' => date('F d, Y'),
    '{{BARCODE}}' => "file:///" . str_replace("\\", "/", __DIR__) . "profile/$id_number/{$id_number}_bcode.jpg",
    '{{SIGNATURE}}' => "file:///" . str_replace("\\", "/", __DIR__) . "profile/$id_number/{$id_number}_signature.png",
  ]);

  // Output results
  if ($returnCode === 0 && $returnCodeBack === 0) {
  //if ($returnCode === 0) {
    $imageToPDF = new ImageToPDF($id_number);
    $imageToPDF->generatePDF();

    // echo "ID images created: $outputImage";
  } else {
    echo "Error generating image(s):<br>";
    // echo nl2br(htmlspecialchars(implode("\n", array_merge($output, $outputBack))));
    echo nl2br(htmlspecialchars(implode("\n", array_merge($output, $outputBack))));
  }




?>



