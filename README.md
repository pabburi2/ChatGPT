# Chat GPT
이 문서는 틀려도 사람이 다시 한번 확인하여 사용하면 되는 생성형 인공지능인 OpenAI사의 Chat GPT 관련 사용법에 대한 것들입니다. 산업에서 명확한 답이 필요한 경우에는 적합하지 않을 수 있습니다. 하지만 생각하지 못한 아이디어를 제공 받거나 글작성등 도움을 받아야 할 경우 주변의 ??보다 좋은 선택이 될 수 있습니다.<br>
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

1. Chat GPT의 API를 직접 이용하는 방법(권장)

여기서 권장하는 방법은 Chat GPT의 API를 직접 이용하는 방법을 권장 합니다. HTTP 프로토콜을 이용한 json 데이터를 전달하는 방식을 사용합니다. 네이버나 다음카카오 API 사용해보신 분이 충분히 쉽게 접근 가능 합니다.
<br>
<br>

## 모델의 종류
- 현재 OpenAPI에서 서비스 가능한 모델에 대한 것이 있으며 일부는 특정 사용자에게 오픈한 것도 있습니다.<br>
  https://platform.openai.com/docs/models/overview
  <br>

- 내가 사용가능한 모델 확인하는 방법<br>
  https://platform.openai.com/docs/api-reference/models<br>
  curl https://api.openai.com/v1/models -H "Authorization: Bearer $OPENAI_API_KEY"
