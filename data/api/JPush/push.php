<?php
require_once('Config.php');
require_once('Http.php');
require_once('Exceptions/JPushException.php');
require_once('Exceptions/APIConnectionException.php');
require_once('Exceptions/APIRequestException.php');
require_once('Client.php');
require_once('PushPayload.php');

function jg_push($title,$content,$registration_id){
    $app_key = '9b5c8730348fd295ef56dc13';
    $master_secret = 'af45d1ec614ba311d3de3b47';

    $client = new Client($app_key, $master_secret);
    try {
        if ($registration_id == 'all') {
            $push_payload = $client->push()
                ->setPlatform('all')
                ->addAllAudience()
                ->setNotificationAlert($content);
            $response = $push_payload->send();
        } elseif($registration_id) {
            $response = $client->push()
                ->setPlatform(array('ios', 'android'))
                ->addRegistrationId($registration_id)
                ->setNotificationAlert($title)
                ->iosNotification($content, array(
                    'badge' => '+1',
                    'content-available' => true
                ))
                ->androidNotification($content)
                ->message($content)
                ->send();
        }
    }catch (Exception $ex){
        echo $ex->getMessage();
    }
    return $response;
}
