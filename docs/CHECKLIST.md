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

## Phase 1. 기본 HTML 구조 (UI 뼈대) ✅

- [x] `index.php` 파일 생성 ✅
- [x] HTML5 기본 구조 작성 (`<!DOCTYPE html>`, 인코딩 UTF-8) ✅
- [x] `<meta viewport>` 태그 추가 (모바일 최적화 필수) ✅
- [x] Bootstrap 5 CDN 연결 ✅ (v5.3.3 + Bootstrap Icons + Google Fonts)
- [x] 페이지 제목 및 기본 레이아웃 구성 ✅ (풍수 다크 테마 적용)
- [x] 업로드 영역 · 로딩 · 결과 · 에러 영역 HTML 골격 사전 구성 ✅
- [ ] ⚠️ `index.html` 파일 삭제 필요 → `rm /var/www/html/bbb/index.html`

---

## Phase 2. 이미지 업로드 UI ✅

- [x] 파일 업로드 `<input type="file" accept="image/jpeg,image/png,image/webp,image/gif">` 구현 ✅
- [x] 모바일에서 카메라/갤러리 선택 가능 확인 ✅ (`accept` 속성으로 자동 지원, capture 미사용으로 카메라+갤러리 모두 선택 가능)
- [x] 업로드 전 **클라이언트 사이드 유효성 검사** 구현 (JavaScript) ✅
  - [x] 파일 미선택 시 안내 메시지 ✅
  - [x] 파일 크기 5MB 초과 시 안내 메시지 (실제 크기 MB 표시) ✅
  - [x] 이미지 형식 아닐 경우 안내 메시지 ✅ (MIME 타입 체크)
- [x] '운기 분석 시작하기' 버튼 구현 ✅ (유효한 파일 선택 시에만 활성화)
- [x] 업로드 영역 UI 스타일링 ✅ (에러 시 빨간 테두리, fadeIn 애니메이션)
- [x] 드래그 앤 드롭 지원 ✅ (드롭된 파일도 유효성 검사 적용)

---

## Phase 3. 로딩 UI ✅

- [x] 로딩 스피너 컴포넌트 구현 ✅ (CSS 순수 애니메이션, 골드 컬러)
- [x] 로딩 메시지 텍스트 표시 ✅ (4가지 메시지 2초마다 순환)
- [x] 분석 요청 중 버튼 **비활성화** 처리 ✅ (폼 전체 숨김으로 클릭 원천 차단)
- [x] 로딩 시작/종료 시점에 맞춰 스피너 표시/숨김 처리 ✅ (`showLoading()` / `hideLoading()` 함수 구현)

---

## Phase 4. PHP 서버 로직 (OpenAI API 연동) ✅

- [x] `.env` 파일에서 API 키 읽어오기 ✅ (직접 파싱, Composer 불필요)
- [x] POST 요청으로 업로드된 이미지 수신 처리 ✅ (`$_FILES` + 서버 사이드 이중 검증)
- [x] 이미지를 **Base64**로 인코딩 ✅
- [x] OpenAI API 요청 JSON Payload 생성 ✅
  - [x] system 프롬프트 작성 ✅ (JSON 형식 응답 요청, 한국어, 긍정적 톤)
  - [x] user 메시지에 이미지 데이터 포함 ✅ (`detail: low` 토큰 절약)
- [x] `cURL`로 OpenAI API 호출 ✅ (타임아웃 30초, 연결 10초)
- [x] API 응답 JSON 디코딩 및 파싱 ✅
  - [x] `score` 값 추출 ✅ (0~100 범위 클램핑)
  - [x] `advice` 배열 추출 ✅ (최대 2개 슬라이싱)
- [x] 마크다운 코드블록 제거 처리 ✅ (AI가 ```json ... ``` 로 감쌀 때 대비)
- [x] JS `fetch()` AJAX 전송으로 교체 ✅ (로딩 UI와 완전 연동)

---

## Phase 5. 결과 출력 UI ✅

- [x] 분석 결과 영역 구현 ✅ (초기 숨김 → 결과 수신 시 `fadeInUp` 애니메이션으로 등장)
- [x] **풍수 점수** 표시 ✅ (0에서 목표 점수까지 카운트업 애니메이션)
- [x] 점수별 이모지 표시 ✅ (🌟대길 / ✨좋음 / 🌿보통 / 🔮주의 / 🌀걱정)
- [x] **인테리어 조언 2가지** 목록 표시 ✅ (AI 응답 텍스트 동적 삽입)
- [x] 줄바꿈 등 텍스트 포맷 유지 ✅ (`white-space: pre-wrap` CSS 적용)
- [x] 결과 수신 후 자동 스크롤 처리 ✅ (`scrollIntoView` smooth - 모바일 UX)

---

## Phase 6. 에러 처리 ✅

- [x] API 호출 실패 시 에러 메시지 표시 ✅ (fetch catch 및 cURL 에러 상태 처리 완료)
- [x] API 응답이 예상 JSON 형식이 아닐 경우 예외 처리 ✅ (파싱 실패 시 예외 던짐)
- [x] 에러 발생 시 버튼 **재활성화** 처리 ✅ (hideLoading() 활용)
- [x] PHP 에러 로그 확인 (`error_log()` 활용) ✅ (API 응답 실패 시 로그 기록)

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
