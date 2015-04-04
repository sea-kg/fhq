<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$curdir = dirname(__FILE__);
include_once ($curdir."/../api.lib/api.base.php");
include_once ($curdir."/../api.lib/api.helpers.php");
include_once ($curdir."/../api.lib/api.security.php");
include_once ($curdir."/../api.lib/api.user.php");
include_once ($curdir."/../../config/config.php");

$result = array(
	'result' => 'fail',
	'data' => array(),
);

$token = '';

if (!APIHelpers::issetParam('email'))
	APIHelpers::showerror(1001, 'Parameter email was not found');

if (!APIHelpers::issetParam('password'))
	APIHelpers::showerror(1316, 'Parameter password was not found');

$email = APIHelpers::getParam('email', '');
$password = APIHelpers::getParam('password', '');
$conn = APIHelpers::createConnection($config);
$hash_password2 = APISecurity::generatePassword2($email, $password);
if( APISecurity::login($conn, $email, $hash_password2)) {
	$result['result'] = 'ok';
	$token = APIHelpers::gen_guid();
	$result['data']['token'] = $token;
} else {
	APIHelpers::showerror(1002, 'email {'.$email.'} and password was not found in system ');
}


if ($result['result'] == 'ok') {
	APISecurity::insertLastIp($conn, APIHelpers::getParam('client', 'none'));
	APIUser::loadUserProfile($conn);
	// APIUser::loadUserScore($conn);
	APISecurity::saveByToken($conn, $token);
}

echo json_encode($result);
