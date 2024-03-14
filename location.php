<?php

$location = $_POST['location'];

if ($location == "") {
	echo json_encode(['status' => 'error', 'message' => "Please enter location."], 403);
}

$url = "https://api.weatherapi.com/v1/forecast.json";

$requestArr = [
	'key' => '982bd473a9464539a2f104834241403',
	'q' => $location,
	'days' => 7
];

$requestParam = http_build_query($requestArr);

$apiUrl = $url .'?'.$requestParam;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($requestArr));

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$responseData = json_decode($response, true);

$responseArr = [];

if ($httpcode == '200') {
	$day = date('l', strtotime($responseData['location']['localtime']));
	$responseArr['current'] = [
		'current_data' => $day.' '.$responseData['location']['localtime'],
		'location' => $responseData['location']['name'],
		'temperature' => $responseData['current']['condition']['text']
	];
	$foreCast = $responseData['forecast']['forecastday'];

	$forecastArr = [];
	foreach ($foreCast as $key => $value) {
		$forecastArr[$key]['day'] = date('l', strtotime($value['date']));
		$forecastArr[$key]['temperature'] = $value['day']['condition']['text'];
	}

	usort($forecastArr, function($a, $b) {
		$daysOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
    	$dayAIndex = array_search($a['day'], $daysOfWeek);
    	$dayBIndex = array_search($b['day'], $daysOfWeek);
    	return $dayAIndex - $dayBIndex;
	});

	$responseArr['forecast'] = array_column($forecastArr, 'temperature');

	echo json_encode(['status' => 'success', 'data' => $responseArr], 200);
} else {
	echo json_encode(['status' => 'error', 'message' => $responseData['error']['message']], 500);
}

?>