<?php
$data = [
	'message' => $message,
	'url' => $url,
	'title' => CUSTOM_ANDROID_TITLE,
	'sound' => $sound,
];

$notification = [
	'title' => CUSTOM_ANDROID_TITLE,
	'body' => $message 
];

/*
	registration ids: an array of the registration id of the devices which will receive the notification
	data: include this if you are going to send a link or play a specific notification sound on the device
	notification: include this if you want the notification to appear on the notification shade when the app is closed or in the background
*/
$fields = [
	'registration_ids' => [ $push_id ],
	'data' => $data, 
	'notification' => $notification
];

$headers = [
	'Authorization: key=' . CUSTOM_ANDROID_API_ACCESS_KEY,
	'Content-Type: application/json'
];

$fields = json_encode ( $fields );

$ch = curl_init();
curl_setopt ( $ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
curl_setopt ( $ch, CURLOPT_POST, true );
curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // this isn't critical because we don't send secure information via the push * setting this to true will not have a succesful push :(
curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

$text = curl_exec ( $ch );
$result = json_decode ( $text, true );

//detect invalid registration ids due to uninstallation of the app
$result_message = $result['results'][0];
//if ( array_key_exists ( 'error', $result_message )
//$result_message['error'] == 'InvalidRegistration'
//$result_message['error'] == 'NotRegistered'
curl_close ( $ch );
?>