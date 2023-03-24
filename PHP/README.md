# 자바스크립트 스트림 데이터 처리
실시간으로 전달되는 스트림에 처리는 jQuery의 ajax 같은것을 사용하는 것이 아니라 이것을 사용해야 합니다.<br>


**▷ 참고할 부분**<br>
- 실시간으로 청크 디코드 부분이 중요합니니다. 모두 받아진 다음 디코드 하는것은 ChatGPT에서 필요 하지 않기 때문입니다.<br>
- 데이터가 서버에서 받아지는대로 실시간으로 청크디코드가 이뤄줘야 합니다.<br>
<br>

**▷ 첨부된 소스파일은**<br>
- 아래 페이지에 있는것을 가져온것으로 문제 없는지 확인한 프로그램으로 테스트로 사용해도 무방합니다.<br>
- Server-sent_events [이벤트소스 샘플 페이지 주소](https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events)
- PHP 헤더에 이벤트 스트림이 있는 것을 확인 하세요.
```php
<?php
  header("Content-Type: text/event-stream");
?>
```
- 그리고 아래 부분도 무척 중요합니다. 출력할 수 있도록 처리해 주지 않으면 원하는 형태의 결과를 얻을 수 없습니다.
```php
<?php
        ob_end_flush();
        flush();
?>
```
- 이벤트 스트림 출력할때 줄바꿈이 한개 또는 두개 있어야 하는 부분등은 테스트를 통해서 확인해 보시면 됩니다.
<br><br>

**▷ PHP출력 하는데 스트림이 작동을 하지 않을때**<br>
- [버퍼출력과 관련된 것들](https://stackoverflow.com/questions/4870697/php-flush-that-works-even-in-nginx) 설정에 따라 안되는 경우는 혹시 설정 문제 아닌가 확인해 볼 필요가 있습니다.
- 설정 문제는 크게 두가지 입니다.(압축, 버퍼사용)
```
In php.ini:
. output_buffering = Off
. zlib.output_compression = Off

In nginx.conf:
. gzip off;
. proxy_buffering off;

Also have this lines at hand, specially if you don't have acces to php.ini:
@ini_set('zlib.output_compression',0);
@ini_set('implicit_flush',1);
@ob_end_clean();
set_time_limit(0);

Last, if you have it, coment the code bellow:
ob_start('ob_gzhandler');
ob_flush();
```
