<?php
ob_start();
header("Access-Control-Allow-Origin: *");
header("content-type:text/javascript;charset=utf-8");
header("Content-Type: application/json; charset=utf-8", true, 200);
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

require_once 'auth/config.ini.php';
require 'vendor/autoload.php';

date_default_timezone_set("Asia/Bangkok");
$datereal = date('Y-m-d H:i:s');
$datenow = date('Y-m-d');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$data = array(
    'version' => $_ENV['VERSION'],
);

$res = array(
    'data' => $data,
    'result' => true,
    'error' => "success",
);

print json_encode($res);
