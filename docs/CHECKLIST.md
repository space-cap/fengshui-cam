# 개발 체크리스트 (CHECKLIST)
**프로젝트:** AI 풍수지리 인테리어 테스트 (lucky-room)
**목표:** 단일 `index.php` 파일로 당일 MVP 완성

> ✅ 완료 | 🔲 미완료 | ⏳ 진행 중

---

## Phase 0. 환경 설정 (코딩 시작 전)

- [x] `.env` 파일에 OpenAI API 키 입력 ✅
  ```
  OPENAI_API_KEY=sk-xxxxxxxxxxxxxxxx
  ```
- [x] `.env` 파일이 `.gitignore`에 포함되어 있는지 확인 ✅
- [x] PHP에서 `cURL` 확장이 활성화되어 있는지 확인 ✅ (PHP 8.3.6, cURL 활성화됨)
  ```bash
  php -m | grep curl
  ```
- [x] 웹 서버(Apache)에서 정상 동작 확인 ✅ (DocumentRoot: /var/www/html/bbb → http://localhost/)

---

## Phase 1. 기본 HTML 구조 (UI 뼈대)

- [ ] `index.php` 파일 생성
- [ ] HTML5 기본 구조 작성 (`<!DOCTYPE html>`, 인코딩 UTF-8)
- [ ] `<meta viewport>` 태그 추가 (모바일 최적화 필수)
- [ ] Bootstrap 5 CDN 연결
- [ ] 페이지 제목 및 기본 레이아웃 구성

---

## Phase 2. 이미지 업로드 UI

- [ ] 파일 업로드 `<input type="file" accept="image/*">` 구현
- [ ] 모바일에서 카메라/갤러리 선택 가능 확인 (`capture` 속성 검토)
- [ ] 업로드 전 **클라이언트 사이드 유효성 검사** 구현 (JavaScript)
  - [ ] 파일 미선택 시 안내 메시지
  - [ ] 파일 크기 5MB 초과 시 안내 메시지
  - [ ] 이미지 형식 아닐 경우 안내 메시지
- [ ] '운기 분석 시작하기' 버튼 구현
- [ ] 업로드 영역 UI 스타일링 (터치하기 쉬운 크기로)

---

## Phase 3. 로딩 UI

- [ ] 로딩 스피너 컴포넌트 구현 (Bootstrap 또는 CSS)
- [ ] "AI가 기운을 살피는 중입니다..." 텍스트 표시
- [ ] 분석 요청 중 버튼 **비활성화** 처리 (`disabled`)
- [ ] 로딩 시작/종료 시점에 맞춰 스피너 표시/숨김 처리

---

## Phase 4. PHP 서버 로직 (OpenAI API 연동)

- [ ] `.env` 파일에서 API 키 읽어오기
  ```php
  $apiKey = getenv('OPENAI_API_KEY');
  ```
- [ ] POST 요청으로 업로드된 이미지 수신 처리 (`$_FILES`)
- [ ] 이미지를 **Base64**로 인코딩
  ```php
  $base64 = base64_encode(file_get_contents($tmpPath));
  ```
- [ ] OpenAI API 요청 JSON Payload 생성
  - [ ] system 프롬프트 작성 (JSON 형식 응답 요청, 한국어, 긍정적 톤)
  - [ ] user 메시지에 이미지 데이터 포함
- [ ] `cURL`로 OpenAI API 호출 (`https://api.openai.com/v1/chat/completions`)
- [ ] API 응답 JSON 디코딩 및 파싱
  - [ ] `score` 값 추출
  - [ ] `advice` 배열 추출

---

## Phase 5. 결과 출력 UI

- [ ] 분석 결과 영역 구현 (초기엔 숨김 처리)
- [ ] **풍수 점수** 표시 (숫자 강조)
- [ ] **인테리어 조언 2가지** 목록 표시
- [ ] 줄바꿈 등 텍스트 포맷 유지 (`nl2br` 또는 CSS `white-space`)
- [ ] 결과 수신 후 자동 스크롤 처리 (모바일 UX)

---

## Phase 6. 에러 처리

- [ ] API 호출 실패 시 에러 메시지 표시
- [ ] API 응답이 예상 JSON 형식이 아닐 경우 예외 처리
- [ ] 에러 발생 시 버튼 **재활성화** 처리
- [ ] PHP 에러 로그 확인 (`error_log()` 활용)

---

## Phase 7. 최종 점검 및 완성

- [ ] **모바일** 실제 기기에서 테스트 (카메라 촬영 → 분석 → 결과)
- [ ] **PC 브라우저**에서 테스트 (파일 업로드 방식)
- [ ] 느린 네트워크 환경에서 로딩 UI 정상 동작 확인
- [ ] API 키가 소스 코드에 하드코딩되지 않았는지 최종 확인 🔐
- [ ] Git 커밋 전 `.env` 파일 제외 여부 확인
  ```bash
  git status
  ```

---

## 🗒️ AI 프롬프트 메모 (Phase 4 작업 시 참고)

```
[System Prompt 초안]
당신은 한국의 풍수지리 전문가입니다.
사용자가 업로드한 방 사진을 분석하여 아래 JSON 형식으로만 응답하세요.
응답 언어는 반드시 한국어, 톤은 긍정적이고 재미있게 작성하세요.

{
  "score": (0~100 사이의 정수),
  "advice": [
    "조언 1",
    "조언 2"
  ]
}
```

---

## 📌 완성 기준 (Definition of Done)

> 아래 세 가지를 모두 만족하면 **MVP 완성!** 🎉

- [ ] 모바일에서 사진을 찍어 업로드하면 풍수 점수와 조언이 출력된다.
- [ ] API 키가 외부에 노출되지 않는다.
- [ ] 에러 발생 시 사용자에게 친절한 메시지가 표시된다.
