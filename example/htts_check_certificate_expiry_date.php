<?php
/**
 * ---------------------------------------------------------------------------
 *
 *  리눅스 쉘에서 절차형으로 간단하게 사용하기 좋은 형태
 *  PHP-SSL-HTTPS-Check-certificate-expiry-date
 *
 *  Date  : 2023.03.12
 *  Author: pabburi.co.kr
 *
 * ---------------------------------------------------------------------------

 * ---------------------------------------------------------------------------
 */

set_time_limit(0);
ini_set('memory_limit','2048M');
setlocale(LC_CTYPE, 'ko_KR.utf8');
date_default_timezone_set('Asia/Seoul');
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
ini_set("display_errors", 1);

if ( !isset($_ENV['HOSTNAME']) ) $_ENV['HOSTNAME'] = '';
$HOST_NAME  = ($_ENV['HOSTNAME']) ? $_ENV['HOSTNAME']:php_uname('n');
$_PID_      = __DIR__ . '/pid';

#
$MODE       = '';
$TYPE       = '';
if ( isset($argv[1]) ) $MODE = $argv[1];
if ( isset($argv[2]) ) $TYPE = $argv[2];


# -----------------------------------------------------------------------------
#
# -----------------------------------------------------------------------------
#
# ./htts_check_certificate_expiry_date.php ssl_check
if ( $MODE == 'ssl_check' )
{
  define('CHK_DAY_NUM', 15);

  # 1월부터 치환을 하면 안된다 - 1월이 먼저 되면 11월은 안되기 때문에
  $aMonthName   = array();
  $aMonthName['10월']  = 'Oct';
  $aMonthName['11월']  = 'Nov';
  $aMonthName['12월']  = 'Dec';
  $aMonthName['1월']   = 'Jan';
  $aMonthName['2월']   = 'Feb';
  $aMonthName['3월']   = 'Mar';
  $aMonthName['4월']   = 'Apr';
  $aMonthName['5월']   = 'May';
  $aMonthName['6월']   = 'Jun';
  $aMonthName['7월']   = 'Jul';
  $aMonthName['8월']   = 'Aug';
  $aMonthName['9월']   = 'Sep';

  #
  $fileName     = __DIR__ . '/test-HttsCheckCertificateExpiryDate_check_list.txt';
  if ( is_file($fileName) )
  {
    $fpHandle     = fopen($fileName, "r");
    while (( $aData=fgetcsv($fpHandle, 4096, "," )) !== FALSE )
    {
      $hostAddr     = trim($aData[0]);
      if ( strlen($hostAddr) < 5 ) continue;
      if ( substr($hostAddr, 0, 1) == '#' ) continue;

      #
      $cmd2       = "curl https://$hostAddr/robots.txt -vI --stderr - | grep \"expire date\"";
      $rCmd2      = shell_exec($cmd2);
      $temp       = str_replace('*  expire date:', '', $rCmd2);
      $temp       = str_replace('*', '', $temp);
      $temp       = str_replace('expire date:', '', $temp);
      $temp       = str_replace('expire date:', '', $temp);
      foreach( $aMonthName as $mnthName => $engName ) {
        $temp       = str_replace($mnthName, $engName, $temp);
      }
      $temp       = trim($temp);
      $uxTimeExpr = strtotime($temp);

      # ?일 이내면 - 변수처리 하여 뒤쪽에 붙여야 한다
      $checkDay    = '';
      if ( $uxTimeExpr < time() + 86400 * CHK_DAY_NUM ) {
        $checkDay      = '** ' . CHK_DAY_NUM . '일 이내 **';
      }

      # 메시지를 보내준다 - 텔레그렘, SMS, ... 사용가능한것
			$expireDayNum	= floor(($uxTimeExpr - time())/86400);
			if ( $expireDayNum < 10 ) {
				$msg          = date('Y-m-d H:i:s', $uxTimeExpr) . " $hostAddr - 남은기간:{$expireDayNum}일" . PHP_EOL;
			}

      echo date('Y-m-d H:i:s', $uxTimeExpr ) . " (남은날짜:{$expireDayNum}일) " .  $hostAddr  . ' => ' . $temp .  " $checkDay " . PHP_EOL;
      // exit;
    }
  }
  else {
    echo date('Y-m-d H:i:s ') . '파일 없음: ' . $fileName . PHP_EOL;
  }

}