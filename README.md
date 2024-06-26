# Chat GPT
이제는 너무 많이 알려진 ChatGPT에 대한 부분으로 어떤 것이든지 많이 사용해 익숙해지면 쉽습니다.<br>
<br>
API 키 얻는 방법에 대해선 이 문서에선 다루지 않습니다.<br>
  https://platform.openai.com/account/api-keys<br>
  **내 API키가 잘 작동하는지 확인 하려면 아래쪽 사용가능 모델 확인 하는 CURL을 실행해 보세요.**<br>
<br>

API 사용은 크게 두가지 형태가 있습니다.
<br>
1. API를 이용해서 API를 만든 형태<br>
  https://platform.openai.com/docs/libraries<br>
  각종 언에에 대한 API를 소개 하고 있습니다. - 개인적으로 권장하지 않습니다. 사용이 힘든것도 있기 때문입니다.<br>
  <font color='red'>( 쉬운것을 어렵게 사용해야 되는 경우가 있을 수 있음 ) </font>

1. **Chat GPT의 API를 직접 이용하는 방법(권장 - 쉬운방법)**<br>
여기서 권장하는 방법은 Chat GPT의 API를 직접 이용하는 방법을 권장 합니다. <br>
HTTP 프로토콜을 이용한 json 데이터를 전달하는 방식을 사용합니다. 네이버나 다음카카오 API 사용해보신 분이 충분히 쉽게 접근 가능 합니다.
<br>
<br>

## 모델의 종류
- 현재 OpenAPI에서 서비스 가능한 모델에 대한 것이 있으며 일부는 특정 사용자에게 오픈한 것도 있습니다.<br>
  https://platform.openai.com/docs/models/overview
  <br>

- 내가 사용가능한 모델 확인하는 방법<br>
  https://platform.openai.com/docs/api-reference/models<br>
```bash
  curl https://api.openai.com/v1/models -H "Authorization: Bearer $OPENAI_API_KEY"
```


### GPT4 vision
시각지능에 대한 것이다. 모르는 도표를 올려 보고 설명해 달라고 하면 생각보다 설명을 잘 하는 편인데 물론 올린 것에 대한 지식은 약간 가지고 있어야 한다.
* 아래는 픽사베이의 이미지 주소를 넣어주고. 새가 몇마리 인지 물어 본것이다.<br>
  <a href="https://www.pabburi.co.kr/content/pds/openai-gpt4-vision-%EC%8B%9C%EA%B0%81%EC%A0%95%EB%B3%B4-api-%EC%82%AC%EC%9A%A9%ED%95%B4%EB%B3%B4%EA%B8%B0/">GPT4 Vision 시각정보 API</a>
```bash

  curl https://api.openai.com/v1/chat/completions \
    -H "Content-Type: application/json" \
    -H "Authorization: Bearer API키" \
  -d '{"model":"gpt-4-vision-preview","messages":[{"role":"user","content":[{"type":"text","text":"새가 몇 마리"},{"type":"image_url","image_url":{"url":"https://cdn.pixabay.com/photo/2014/11/21/15/39/grey-crowned-cranes-540657_1280.jpg","detail":"high"}}]}],"max_tokens":300}'

```

## API 출력종류
- stream은 사이트 들어가면 한글자씩 나오는 방식. 자바스크립트에서 EventSource 를 사용해야 합니다.
- 지정하지 않으면 한번에 나오는 방식으로 느린 결과를 얻습니다. 단, 위 스트림과는 결과적으로 같은 시간에 끝날 수도?
