# Content-Length VS Transfer-Encoding: chunked
HTTP 프로토콜 사용을 하는 서버에서 출력을 해주는 방법에는 두가지가 있습니다.
1. Content-Length
   한번에 모든 결과를 출력합니다. 그렇기 때문에 이런 형태로 만들게 되면 서버에서 늦게 실행이 된다면 웹사이트의 경우 힌색 화면이 보이고 있는 상태에서 팍! 보이는 형태로 생각하시면 되며 서버에서 미리 html 페이지를 만들어 놓았다면 클릭하자 마다 페이지가 보이게 될것입니다.<br>
   압축전송을 하는 경우는 이런 형태 입니다. 압축을 중간중간 하지는 않기 때문입니다.
1. chunked
   서버에서 처리 되는데로 출력 합니다. 그렇기 때문에 상단 헤더부분이 마무리 되었으면 상단 헤더 부분만 먼저 보입니다. 사용자 입장에서 지루하지 않고 좋습니다. 일반적으로 동적인 처리는 모두 이 방식 입니다.


**▷ Content-Length**<br>
특징은 전송되는 바이트를 서버에서 알려 준다는 것입니다. 그러면 클라이언트에선 알려준 길이 만큼만 사용하면 됩니다.
예를 들어 서버에선 100이라고 알려주고 200을 보내면 어떻게 되느냐 궁금할 수 있을 텐데요. 클라이언트는 서버가 100이라고 하였으니 더 보내준것은 무시하는것이 맞습니다. 이부분은 클라이언트에서 서버로 보낼때도 그렇습니다.
```
아래는 chunked를 변환을 한것입니다.
그렇기 때문에 Content-Type: text/event-stream 이부분과 Content-Length 이 같이 있다는 것은 잘못 된것입니다.
있다고 하여 클라이언트에서 데이터를 못 받는것은 아니지만 원하는 형태가 결과가 나오지 않을 수 있기 때문인데요.
빠르게 딱 나오면 문제가 될것 없을 수 있습니다.

HTTP/1.1 200 OK
Date: Fri, 24 Mar 2023 22:54:27 GMT
Content-Type: text/event-stream
Connection: keep-alive
Access-Control-Allow-Origin: https://platform.openai.com
Cache-Control: no-cache, must-revalidate
Openai-Model: gpt-3.5-turbo-0301
Openai-Organization: user-oz3myortv0vec2kj2d4rnm1y
Openai-Processing-Ms: 185
Openai-Version: 2020-10-01
Strict-Transport-Security: max-age=15724800; includeSubDomains
Vary: Origin
X-Ratelimit-Limit-Requests: 3500
X-Ratelimit-Remaining-Requests: 3499
X-Ratelimit-Reset-Requests: 17ms
X-Request-Id: 81f705f0b4ceabb35bac41cb6e0248a1
Content-Length: 838

data: {"id":"chatcmpl-6xkahElvH76lA63ihXasa0U92zD81","object":"chat.completion.chunk","created":1679698467,"model":"gpt-3.5-turbo-0301","choices":[{"delta":{"role":"assistant"},"index":0,"finish_reason":null}]}

data: {"id":"chatcmpl-6xkahElvH76lA63ihXasa0U92zD81","object":"chat.completion.chunk","created":1679698467,"model":"gpt-3.5-turbo-0301","choices":[{"delta":{"content":"예"},"index":0,"finish_reason":null}]}

data: {"id":"chatcmpl-6xkahElvH76lA63ihXasa0U92zD81","object":"chat.completion.chunk","created":1679698467,"model":"gpt-3.5-turbo-0301","choices":[{"delta":{"content":"."},"index":0,"finish_reason":null}]}

data: {"id":"chatcmpl-6xkahElvH76lA63ihXasa0U92zD81","object":"chat.completion.chunk","created":1679698467,"model":"gpt-3.5-turbo-0301","choices":[{"delta":{},"index":0,"finish_reason":"stop"}]}

data: [DONE]

```

**▷ Transfer-Encoding: chunked**<br>
서버에서 출력한 대로 바로 클라이언트로 보내주는데 그 양이 정해져 있지 않고 그때 그때 다릅니다. <br>
서버에서의 버퍼설정등 여러가지 상황에 따라 다르고 아래 보시면 알겠지만 중간중간 16진수가 보일겁니다. <br>
그리고 맨 끝은 더 이상 데이터가 없다는 의미로 0을 찍어 줍니다.
<br><br>
예를 들면 아리쪽 data: [DONE] 부분 위쪽에 보면 e 라고 되어 있지요. <br>
이건 10진수로 하면 14입니다. <br>
그러면 그 다음 부터 14바이트를 처리하라는 의미 입니다. 제가 세어보니 눈에 보이지 않는 newline 포함하면 딱 맞습니다.
```
HTTP/1.1 200 OK
Date: Fri, 24 Mar 2023 22:54:27 GMT
Content-Type: text/event-stream
Transfer-Encoding: chunked
Connection: keep-alive
Access-Control-Allow-Origin: https://platform.openai.com
Cache-Control: no-cache, must-revalidate
Openai-Model: gpt-3.5-turbo-0301
Openai-Organization: user-oz3myortv0vec2kj2d4rnm1y
Openai-Processing-Ms: 185
Openai-Version: 2020-10-01
Strict-Transport-Security: max-age=15724800; includeSubDomains
Vary: Origin
X-Ratelimit-Limit-Requests: 3500
X-Ratelimit-Remaining-Requests: 3499
X-Ratelimit-Reset-Requests: 17ms
X-Request-Id: 81f705f0b4ceabb35bac41cb6e0248a1

1a5
data: {"id":"chatcmpl-6xkahElvH76lA63ihXasa0U92zD81","object":"chat.completion.chunk","created":1679698467,"model":"gpt-3.5-turbo-0301","choices":[{"delta":{"role":"assistant"},"index":0,"finish_reason":null}]}

data: {"id":"chatcmpl-6xkahElvH76lA63ihXasa0U92zD81","object":"chat.completion.chunk","created":1679698467,"model":"gpt-3.5-turbo-0301","choices":[{"delta":{"content":"예"},"index":0,"finish_reason":null}]}


cf
data: {"id":"chatcmpl-6xkahElvH76lA63ihXasa0U92zD81","object":"chat.completion.chunk","created":1679698467,"model":"gpt-3.5-turbo-0301","choices":[{"delta":{"content":"."},"index":0,"finish_reason":null}]}


c4
data: {"id":"chatcmpl-6xkahElvH76lA63ihXasa0U92zD81","object":"chat.completion.chunk","created":1679698467,"model":"gpt-3.5-turbo-0301","choices":[{"delta":{},"index":0,"finish_reason":"stop"}]}


e
data: [DONE]


0

```
