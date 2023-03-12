# PHP-SSL-HTTPS-Check-certificate-expiry-date
웹서버의 SSL 인증서 만료되었는지 체크 하는 프로그램 입니다. 만료날짜가 제각각 이고 관리하는 도메인이 은근히 많은 경우 유용합니다. <br>
프로그램은 충분히 간단하게 만들어졌기 때문에 수정하여 사용하시면 됩니다.

## 실행방법
```
1) 체크할 도메인을 아래 파일에 적습니다.
   https_check_list.txt

2) 쉘에서 직접 실행 합니다.
   리눅스에서 실행하게 되면 최상단에 PHP 경로에 대한 부분을 본인 상황에 맞게 수정해 줍니다.

 php ./htts_check_certificate_expiry_date.php ssl_check

2023-11-08 08:59:59 (남은날짜:240일) www.pabburi.co.kr => Nov  7 23:59:59 2023 GMT
2023-06-08 08:59:59 (남은날짜:87일) www.naver.com => Jun  7 23:59:59 2023 GMT
2023-08-31 08:59:59 (남은날짜:171일) www.daum.net => Aug 30 23:59:59 2023 GMT

```

## 참고할것
  - 첫번째 문자가 #(샵) 이면 주석으로 인식하고 처리 하지 않습니다.
  - 체크 할 만료 날짜는 충분하게 최소 2주 이상 주는 것이 좋습니다.
  - curl을 사용하기 때문에 관련 라이브러리가 이미 설치가 되어 있어야 합니다.
  - 만료 날짜가 얼마 남지 않았으면 담당자에게 메시지를 보내줍니다.<br>
    최근엔 슬렉, 텔레그램, 네이버워크, 카카오워크 등 무료로 받아 볼 수 있는 방법들이 많습니다.

  - 카카오워크 메시지 보내기<br>
    https://www.pabburi.co.kr/content/php/%EC%B9%B4%EC%B9%B4%EC%98%A4%EC%9B%8C%ED%81%AC-api-%EB%A6%AC%EC%95%A1%ED%8B%B0%EB%B8%8Creactive-%EB%A9%94%EC%84%B8%EC%A7%80-%EB%B3%B4%EB%82%B4%EA%B8%B0/
  - 텔레그램<br>
    https://www.pabburi.co.kr/content/linux_server/%ED%85%94%EB%A0%88%EA%B7%B8%EB%9E%A8-api-bash%EC%89%98%EC%97%90%EC%84%9C-curl-%ED%99%9C%EC%9A%A9-%EB%A9%94%EC%84%B8%EC%A7%80-%EB%B3%B4%EB%82%B4%EB%8A%94-%EB%B0%A9%EB%B2%95/
  - 네이버윅스<br>
    https://www.pabburi.co.kr/content/linux_server/%EB%84%A4%EC%9D%B4%EB%B2%84%EC%9C%85%EC%8A%A4-api-bash%EC%89%98%EC%97%90%EC%84%9C-curl-%ED%99%9C%EC%9A%A9-%EB%A9%94%EC%84%B8%EC%A7%80-%EB%B3%B4%EB%82%B4%EB%8A%94-%EB%B0%A9%EB%B2%95/

