<?php
ob_start();
header("Access-Control-Allow-Origin: *");
header("content-type:text/javascript;charset=utf-8");
header("Content-Type: application/json; charset=utf-8", true, 200);
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
@$otp_phone = $request->otp_phone;
@$id = $request->id;

require_once '../auth/config.ini.php';
require_once '../auth/function.php';
require '../vendor/autoload.php';

date_default_timezone_set("Asia/Bangkok");
$datereal = date('Y-m-d H:i:s');
$datenow = date('Y-m-d');

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

$API_TOKEN = constant("API_TOKEN");
$API_SERVER = constant("API_SERVER");

if (isset($API_TOKEN, $API_SERVER)) {

    $client = new Client([
        // Base URI is used with relative requests
        'base_uri' => $API_SERVER,
        // You can set any number of default request options.
        'timeout'  => 60.0,
        'verify' => false,
        'headers' => [
            "Content-Type" => "application/json",
            "User-Agent" => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4255.0 Safari/537.36 Edg/87.0.634.0",
            "Origin" => "https://localhost",
            'Authorization' => 'Bearer ' . $API_TOKEN,
            'Accept'        => 'application/json',
            'x-ip-request'        => get_client_ip(),
        ],
        'cookies' => false,
    ]);

    try {
        $raw = array(
            'otp_phone' => $otp_phone,
            'id' => $id,
        );
        $body = json_encode($raw);
        $response =  $client->request('POST', $API_SERVER . "/otp/otp-request.php", [
            'body' => $body,
            ['allow_redirects' => false]
        ]);
        $response = $response->getBody()->getContents();
        $result = json_decode($response, true);
    } catch (ClientException $e) {
        $response = $e->getResponse()->getBody()->getContents();
        $result = json_decode($response, true);
    }

    $res = $result;
} else {
    $result = false;
    $error = "danger";
    $message = "API_TOKEN or API_SERVER is missing";
    $expire = null;
    $ref_no = null;

    $res[] = array(
        'result' => $result,
        'error' => $error,
        'message' => $message,
        'expire' => $expire,
        'ref_no' => $ref_no,
    );
}

print json_encode($res);
