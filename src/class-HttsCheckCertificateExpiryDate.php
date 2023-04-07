<?



/**
 * [Description HttsCheckCertificateExpiryDate]
 */
class HttsCheckCertificateExpiryDate
{
  const CHK_DAY_NUM   = 15;
  const MIN_HOST_LEN  = 5;

  # 1월부터 치환을 하면 안된다 - 1월이 먼저 되면 11월은 안되기 때문에
  private $aMonthName = array(
      '10월'    => 'Oct',
      '11월'    => 'Nov',
      '12월'    => 'Dec',
      '1월'     => 'Jan',
      '2월'     => 'Feb',
      '3월'     => 'Mar',
      '4월'     => 'Apr',
      '5월'     => 'May',
      '6월'     => 'Jun',
      '7월'     => 'Jul',
      '8월'     => 'Aug',
      '9월'     => 'Sep'    
  );
  private $fileName   = '';


  public function __construct( string $fileName )
  {
    $this->fileName   = $fileName;
  }

  /**
   * [Description for chkHttpsHost]
   *
   * @return [type]
   * 
   */
  public function chkHttpsHost()
  {
    $fileName   = $this->fileName;
    $aMonthName = $this->aMonthName;
    $aRtn       = array();

    if ( is_file($fileName) )
    {
      $fpHandle     = fopen($fileName, "r");
      while (( $aData=fgetcsv($fpHandle, 4096, "," )) !== FALSE )
      {
        $hostAddr     = trim($aData[0]);
        if ( strlen($hostAddr) < self::MIN_HOST_LEN ) continue;
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
        if ( $uxTimeExpr < time() + 86400 * self::CHK_DAY_NUM ) {
          $checkDay      = '** ' . self::CHK_DAY_NUM . '일 이내 **';
        }
  
        # 메시지를 보내준다 - 텔레그렘, SMS, ... 사용가능한것
        $expireDayNum	= floor(($uxTimeExpr - time())/86400);
        if ( $expireDayNum < 10 ) {
          $msg          = date('Y-m-d H:i:s', $uxTimeExpr) . " $hostAddr - 남은기간:{$expireDayNum}일" . PHP_EOL;
          $sendStatus   = $this->doTelegram($msg);
        }

        // $aRtn[]       = [
        //     '체크_YMD'      => $uxTimeExpr,
        //     '남은날짜'      => $expireDayNum,
        //     'HostAddr'      => $hostAddr,
        //     'checkDay'      => $checkDay
        // ];
        $aRtn[]       = [
            'uxTimeExpr'      => $uxTimeExpr,
            'expireDayNum'      => $expireDayNum,
            'HostAddr'      => $hostAddr,
            'temp'          => $temp,
            'checkDay'      => $checkDay
        ];

      }
      if ( count($aRtn) > 1 ) return $aRtn;
    }
    else {
      return date('Y-m-d H:i:s ') . '파일 없음: ' . $fileName . PHP_EOL;
    } 

  }


  public function doTelegram( string $msg ) 
  {
    // 생성하는 방법
    // https://www.appletong.com/entry/curl-%EB%AA%85%EB%A0%B9%EC%96%B4%EB%A5%BC-%ED%86%B5%ED%95%B4-telegram-bot%EC%97%90-%EB%A9%94%EC%84%B8%EC%A7%80-%EC%A0%84%EC%86%A1
    
    // GET
    // https://api.telegram.org/bot5585642799:AAGQhdKvZJ3OEbac0PAxxxxxx/sendMessage?chat_id=-1001790970622&text=hello

    // curl
    // curl -k https://api.telegram.org/bot{API_TOKEN}/sendMessage -d "chat_id=-1001790970622" --data-urlencode "text=hello 안녕하세요"
    $sendStatus = false;
    if ( strlen($msg) > 1 ) {
      $chat_id      = '';
      $API_TOKEN    = '';
      $cmd_curl     = "curl -k https://api.telegram.org/bot$API_TOKEN/sendMessage -d \"chat_id=$chat_id\" --data-urlencode \"text=$msg\"";
      $sendStatus   = shell_exec($cmd_curl);
    }

    return $sendStatus;
  }


  public function __destruct()
  {
    
  }

  
}

