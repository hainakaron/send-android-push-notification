<?php
//include this on the payload to make the notification appear on the device's notification tray
$notification = [
	'title' => 'You have a new secure message.',
	'body' => 'Tap to open.' 
];


//include this if you are going to send your own customized payload to the client app. this will be received by the app when it is open. if sent together with the notification 
$data = [
	'message' => 'Hi! How are you? Can you check this link?',
	'url' => 'https://github.com/norhainakaron',
	'title' => 'You have a new secure message.',
	'sound' => 'bicycle', //name of the sound file (bicycle.wav) located in www/locales/android/raw folder for applications made using PhoneGap
];


//place both notification and data in the payload so it will appear on the device's notification tray and when the notification is clicked from there, the app will be opened and the data payload will be processed by the app
$fields = [
	'registration_ids' => [ $push_id ], //an array of the registration id of the devices which will receive the notification
	'notification' => $notification,
	'data' => $data
];

$headers = [
	'Authorization: key=' . 'AlzaSyDngPetnQXKl92GSABSbB7zKEgPP3lw3tg', //this is obtained from your project in the firebase console
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