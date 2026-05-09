<?php

class FetchEmployeeData
{
	protected $id_number;

  public function __construct($idNumber)
	{
		$this->id_number = $idNumber;
	}

	public function fetchEmployeeInfo()
	{
		$url = 'http://localhost:8000/api/employee_details/' . urlencode($this->id_number);
		$ch = curl_init();

		curl_setopt_array($ch, [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTPHEADER => [
				'Accept: application/json'
			]
		]);

		$response = curl_exec($ch);

		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		if ($httpCode !== 200) {
			$_SESSION['response'] = 'API returned HTTP Code: ' . $httpCode;
			header('Location: ../../index.php');
			exit;
		}

		$data = json_decode($response, true);

		if (json_last_error() !== JSON_ERROR_NONE) {
			die('Invalid JSON response');
		}

		return $data;

	}
}

?>