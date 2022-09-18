
<?php

#Global Variables
// pvoutput API key here; OLD
//$Feed_api_key = '091119a8fcds38a06868febe6b4eb3ca';

// pvoutput API key here;
$PV_api_key = '3c4a95d9af09d066ab8007c';

// PVOutput.orgSystem ID here
$SystemID = '12345';

//EMONCms Local IP Here
$EMONCms_Local_IP = "192.168.15.208";

//EmonCMS Read API Key Here
$EmonCMS_Read_API_Key = "091119a8a06868febe6b4eb3ca";

//Add CronTab Job once complete - */5 * * * * /usr/bin/php -q /home/pi/SCRIPT_NAME.php > /dev/null


$url = "https://pvoutput.org/service/r2/addstatus.jsp";

$json_tpower = file_get_contents("http://192.168.15.208/feed/list.json?apikey=".$EmonCMS_Read_API_Key);
$jsonToArray = json_decode($json_tpower, true);

//Verify if feed source come with empty data
if(empty($jsonToArray)){

	exit();
}

//Array which The PVOUTPUT variables will be populated
$variables = [];

date_default_timezone_set('Australia/Melbourne');
$timezone = date_default_timezone_get();
$date = date('Ymd');
$time = date('H:i');

//Adding Time and Date in variables
$variables["d"] = $date;
$variables["t"] = $time;
//The following values next to 'case' are the names of data feeds within EmonCMS
//Line 54/V4 - This is current power usage
//Line 58/V2 - Solar production in watts
//Line 61/V6 - Voltage
//Line 64/V7 - Extended data - optional and can be deleted.
//Line 67/V7 - Extended data - optional and can be deleted.
//Line 70/V7 - Extended data - optional and can be deleted.
//Line 73/V7 - Extended data - optional and can be deleted.
foreach($jsonToArray as $key => $Feed_value){

	switch ($Feed_value["name"]) {
		
		case 'use':
			
			$variables["v4"] = $Feed_value["value"];
			break;
		case 'solar':
			$variables["v2"] = $Feed_value["value"];
			break;
		case 'vrms':
			$variables["v6"] = $Feed_value["value"];
			break;
		case 'CarPort':
			$variables["v7"] = $Feed_value["value"];
			break;
		case 'Roof':
			$variables["v8"] = $Feed_value["value"];
			break;
		case 'Air Con':
			$variables["v9"] = $Feed_value["value"];
			break;
		case 'price':
			$variables["v10"] = $Feed_value["value"];
			break;
		case 'use':
			// code...
			break;
	}
}

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
 "X-Pvoutput-Apikey: ".$PV_api_key,
 "X-Pvoutput-SystemId: ".$SystemID,
 "Content-Type: application/x-www-form-urlencoded",
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

//Build the query text to be sent in CURL
$data = http_build_query($variables);

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
curl_close($curl);
var_dump($resp);

?>