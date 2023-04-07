<?php 
/**
 * --------------------------------------------------------------------------
 *
 *  https ssl 인증서 만료일 체크
 *
 *  작성자: pabburi.co.kr
 *  작성일: 2023. 00. 00
 *
 * --------------------------------------------------------------------------
 */


set_time_limit(0);
ini_set("display_errors", 1);
ini_set('memory_limit','2048M');
setlocale(LC_CTYPE, 'ko_KR.utf8');
date_default_timezone_set('Asia/Seoul');

if ( !isset($_ENV['HOSTNAME']) ) $_ENV['HOSTNAME'] = '';
if ( !isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) $_SERVER['HTTP_X_FORWARDED_FOR'] = '';
if ( !isset($_SERVER['REMOTE_ADDR']) ) $_SERVER['REMOTE_ADDR'] = '';
$HOST_NAME  = ( $_ENV['HOSTNAME']) ? $_ENV['HOSTNAME']:php_uname('n');
$clientIP   = ( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR'];
$_PID_      = __DIR__ . '/pid';


#
$MODE       = '';
$TYPE       = '';
if ( isset($argv[1]) ) $MODE = $argv[1];
if ( isset($argv[2]) ) $TYPE = $argv[2];





include('../src/class-HttsCheckCertificateExpiryDate.php');

$fileName       = __DIR__ . '/test-HttsCheckCertificateExpiryDate_check_list.txt';
$oHttps         = new HttsCheckCertificateExpiryDate($fileName);
$aHostExpire    = $oHttps->chkHttpsHost();
if ( is_array($aHostExpire) ) {
    foreach( $aHostExpire as $aidx => $hostStatus)
    {
        extract($hostStatus);
        echo date('Y-m-d H:i:s', $uxTimeExpr ) . " (남은날짜:{$expireDayNum}일) " .  $hostAddr  . ' => ' . $temp .  " $checkDay " . PHP_EOL;
    }
}
else 
{
    echo date('Y-m-d H:i:s ') . $aHostExpire . PHP_EOL;
}

