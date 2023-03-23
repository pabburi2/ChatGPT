# 실시간 HTTP 청크 디코더
여기에선 컨텐츠랭스와 청크에 대해서 다루지 않습니다.<br>
샘플 파일은 혹시 필요 할 수 있어 받아 놓은 것이며 봐야 할 부분은 청크 디코드 부분입니다.

**▷ 참고할 부분(fsockopen-http-Chunked-decoding.php)**<br>
- 실시간으로 청크 디코드 부분이 중요합니니다. 모두 받아진 다음 디코드 하는것은 ChatGPT에서 필요 하지 않기 때문입니다.<br>
- 데이터가 서버에서 받아지는대로 실시간으로 청크디코드가 이뤄줘야 합니다.<br>
<br>

**▷ 최신버전의 CURL**<br>
- 최신 버전의 CURL을 사용하면 이 프로그램은 필요 하지 않습니다.<br>
- 아래 PHP 소스에서 스트림으로 받아 오는 부분은 CURLOPT_WRITEFUNCTION 라인 입니다.
- 즉, <u>CURL 라이브러리를 사용하면 청크 같은것은 신경쓸 필요가 없습니다.</u>
```php
<?php
  define('OPENAI_API_KEY', 'api-key');

  $text       = 'hi';

  #
  $aMessage   = array();
  $aMessage[] = ["role" => "user", "content" => $text];
  $aRequest   = array(
    'model'       => "gpt-3.5-turbo",
    'max_tokens'  => 2048,
    'temperature' => 0.8,
    'messages'    => $aMessage,
    'stream'      => true,
  );
  $post_data  = json_encode($aRequest, JSON_UNESCAPED_UNICODE);

  #
  $aHeader    = array();
  $aHeader[]  = "Content-Type: application/json";
  $aHeader[]  = "Authorization: Bearer " . OPENAI_API_KEY;

  $url        = 'https://api.openai.com/v1/chat/completions';
  $ch         = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURL_HTTP_VERSION_1_1, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_HTTPHEADER , $aHeader);
  curl_setopt($ch, CURLOPT_TIMEOUT, 123);
  curl_setopt($ch, CURLOPT_VERBOSE, 0);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
  curl_setopt($ch, CURLOPT_WRITEFUNCTION, "receiveResponse");
  $response     = curl_exec($ch);
  $aHeaderInfo  = curl_getinfo($ch);
  curl_close($ch);


  /**
   * curl CURLOPT_WRITEFUNCTION
   *
   * @param mixed $ch
   * @param mixed $xmldata
   *
   * @return int
   *
   */
  function receiveResponse($ch, $jsonData) {
    echo $jsonData;
    return strlen($jsonData);
  }
```
