# 🏠 Lucky Room — AI 풍수지리 인테리어 테스트

> 사진 한 장으로 보는 재미있는 풍수지리 분석! 📸✨

[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat-square&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![OpenAI](https://img.shields.io/badge/OpenAI-GPT--4o-412991?style=flat-square&logo=openai&logoColor=white)](https://openai.com/)
[![License](https://img.shields.io/badge/license-MIT-green?style=flat-square)](LICENSE)

---

<img width="779" height="1505" alt="localhost_index php" src="https://github.com/user-attachments/assets/43008889-dbfc-459e-99b6-4cca5473803a" />


---

## 📖 프로젝트 소개

**Lucky Room**은 최신 멀티모달 AI 기술을 활용한 퀵 프로젝트입니다.

스마트폰으로 촬영한 방 사진을 업로드하면, OpenAI의 `gpt-4o` Vision 모델이 풍수지리 관점에서 인테리어를 분석하고 **풍수 점수**와 **맞춤 인테리어 조언**을 제공합니다.

- 🎯 **타겟 사용자**: 인테리어에 관심 있고, 가벼운 운세/심리 테스트를 즐기는 모바일 웹 사용자
- ⚡ **개발 철학**: 복잡한 설정 없이, 단일 파일로 당일 MVP 완성

---

## ✨ 주요 기능

| 기능 | 설명 |
|------|------|
| 📷 **이미지 업로드** | 카메라 촬영 또는 갤러리 선택 (드래그 앤 드롭 지원) |
| 🔍 **AI 풍수 분석** | GPT-4o Vision으로 방 사진 분석 (약 3~5초) |
| 🏆 **점수 출력** | 100점 만점의 풍수 점수 (카운트업 애니메이션) |
| 💡 **인테리어 조언** | 재물운/건강운 상승을 위한 구체적인 조언 2가지 |
| ⚠️ **에러 처리** | 파일 크기/형식 검증, API 오류 시 친절한 안내 메시지 |
| ⏳ **로딩 UI** | 분석 중 스피너 + 순환 메시지 표시 (중복 요청 방지) |

---

## 🛠️ 기술 스택

- **Frontend**: HTML5, CSS3, Vanilla JavaScript, Bootstrap 5
- **Backend**: PHP 8.x
- **AI API**: OpenAI API (`gpt-4o` Vision)
- **통신**: 프론트엔드 `fetch` API 및 내부 `api.php`의 `cURL` 통신

---

## 🗂️ 프로젝트 구조

```
lucky-room/
├── index.php          # HTML 뷰 (메인 UI)
├── api.php            # PHP 서버 로직 (API 라우터 및 OpenAI 통신 등)
├── app.js             # 클라이언트 스크립트 (파일 압축, 화면 조작, 비동기 통신)
├── style.css          # 디자인 시스템 및 테마 스타일
├── data/              # 런타임 데이터 (Rate Limiting JSON 등 저장)
│   └── .htaccess      # 데이터 폴더 접근 통제용 보안 파일
├── .env               # 환경 변수 (API 키 및 Daily Rate Limit 설정) ← Git 제외
├── .env.example       # 환경 변수 예시 파일
├── .gitignore         # Git 제외 목록
├── .htaccess          # 루트 디렉토리 Apache 설정
└── docs/
    ├── PRD.md         # 제품 요구사항 정의서
    └── CHECKLIST.md   # 개발 체크리스트
```

---

## 🚀 시작하기

### 1. 사전 요구사항

- PHP 8.x 이상
- Apache 웹 서버 (cURL 확장 활성화 필수)
- OpenAI API 키

```bash
# cURL 확장 활성화 확인
php -m | grep curl
```

### 2. 설치

```bash
# 저장소 클론
git clone https://github.com/your-username/lucky-room.git
cd lucky-room
```

### 3. 환경 변수 설정

```bash
# .env 파일 생성
cp .env.example .env
```

`.env` 파일을 열고 OpenAI API 키를 입력하세요:

```env
OPENAI_API_KEY=sk-xxxxxxxxxxxxxxxxxxxxxxxx
```

> [!CAUTION]
> `.env` 파일은 절대 Git에 커밋하지 마세요! `.gitignore`에 이미 포함되어 있습니다.

### 4. 웹 서버 설정

Apache DocumentRoot를 프로젝트 디렉토리로 설정하거나, 가상 호스트를 구성하세요.

```bash
# 예시: DocumentRoot를 /var/www/html/lucky-room 으로 설정 후 접속
http://localhost/
```

---

## 📱 외부 기기(핸드폰)에서 테스트하기

로컬 서버를 외부 기기에서 접속하는 방법은 두 가지가 있습니다.

### 방법 A. 같은 Wi-Fi 네트워크 (간단·빠름)

PC와 폰이 **같은 Wi-Fi**에 연결되어 있을 때 사용하세요.

**① 서버 PC의 로컬 IP 확인**

```bash
# WSL (Ubuntu) 환경에서 실행
ip addr show eth0 | grep "inet " | awk '{print $2}' | cut -d/ -f1
```

> Windows PowerShell에서 확인하려면:
> ```powershell
> ipconfig
> # "이더넷" 또는 "Wi-Fi" 항목의 IPv4 주소 확인 (예: 192.168.0.10)
> ```

**② 방화벽에서 80번 포트 허용 (Windows)**

```powershell
# PowerShell을 관리자 권한으로 실행
netsh advfirewall firewall add rule name="Apache HTTP" dir=in action=allow protocol=TCP localport=80
```

**③ 폰 브라우저에서 접속**

```
http://192.168.0.10/
```

> [!NOTE]
> IP 주소는 위 명령어로 확인한 실제 값으로 교체하세요. 공유기 환경에 따라 `192.168.x.x` 또는 `10.0.x.x` 대역일 수 있습니다.

---

### 방법 B. ngrok 터널링 (외부 네트워크·원격 테스트)

PC와 폰이 **다른 네트워크**에 있거나, 인터넷을 통해 접속하고 싶을 때 사용하세요.

**① ngrok 설치**

```bash
# Homebrew (macOS)
brew install ngrok

# Windows (Chocolatey)
choco install ngrok

# 또는 공식 사이트에서 직접 다운로드
# https://ngrok.com/download
```

**② ngrok 계정 연결 (최초 1회)**

[ngrok.com](https://ngrok.com) 에서 무료 계정 생성 후 토큰 인증:

```bash
ngrok config add-authtoken <YOUR_NGROK_TOKEN>
```

**③ 터널 시작**

```bash
ngrok http 80
```

**④ 출력된 HTTPS URL로 폰에서 접속**

```
Forwarding  https://abcd-1234.ngrok-free.app → http://localhost:80

# 위 URL을 폰 브라우저에 입력하거나 QR코드로 공유
```

> [!CAUTION]
> ngrok 무료 플랜은 세션마다 URL이 바뀝니다. 터널을 종료하면 URL도 만료됩니다.

> [!WARNING]
> ngrok URL은 인터넷에 공개되므로 테스트가 끝나면 반드시 터널을 종료하세요 (`Ctrl + C`).

---

### 방법 C. Cloudflare Tunnel (안정적·무료·HTTPS 자동)

**설치 없이** `npx` 한 줄로 바로 실행할 수 있습니다.  
ngrok과 달리 **계정·로그인 불필요**, Cloudflare CDN을 통해 빠르고 안정적입니다.

**① 터널 시작 (설치 불필요, 즉시 실행)**

```bash
npx --yes cloudflared tunnel --url http://localhost:80
```


**③ 출력된 URL로 폰에서 접속**

```
+--------------------------------------------------------------------------------------------+
|  Your quick Tunnel has been created! Visit it at (it may take some time to be reachable): |
|  https://random-words-1234.trycloudflare.com                                              |
+--------------------------------------------------------------------------------------------+

# 위 URL을 폰 브라우저에 입력 또는 QR코드로 공유
```

> [!NOTE]
> 임시 터널(`trycloudflare.com`)은 **로그인 없이 무료**로 사용 가능합니다. 터널을 시작할 때마다 URL이 새로 발급됩니다.

> [!TIP]
> **고정 URL이 필요하다면?** Cloudflare 계정에 로그인 후 Named Tunnel을 생성하면 항상 같은 도메인을 사용할 수 있습니다. (무료 플랜 지원)

> [!WARNING]
> 터널이 열려 있는 동안 누구나 URL로 접속 가능합니다. 테스트가 끝나면 반드시 `Ctrl + C`로 종료하세요.

---

### 📊 방법 비교

| | 방법 A (Wi-Fi) | 방법 B (ngrok) | 방법 C (Cloudflare) |
|---|---|---|---|
| **네트워크 조건** | 같은 Wi-Fi 필수 | 인터넷 가능 | 인터넷 가능 |
| **설정 난이도** | ⭐ 쉬움 | ⭐⭐ 보통 | ⭐ 쉬움 |
| **계정 필요** | ❌ 불필요 | ✅ 필요 | ❌ 불필요 (임시 터널) |
| **HTTPS** | ❌ | ✅ | ✅ |
| **속도** | 🚀 빠름 | 🟡 보통 | 🚀 빠름 |
| **URL 고정** | 🟡 IP 고정 시 가능 | ❌ 매번 변경 | ❌ 매번 변경 (임시) |
| **추천 상황** | 혼자 빠른 테스트 | 팀원과 공유 | 팀원과 공유 |

---

## 🔄 사용자 플로우

```
1. 메인 웹페이지 접속
       ↓
2. '사진 업로드' 영역 터치 → 카메라 촬영 또는 사진 선택
       ↓
3. 파일 크기(5MB 이하) 및 형식 자동 검사
  ├─ 실패 → 안내 메시지 출력 후 중단
  └─ 성공 ↓
4. '운기 분석 시작하기' 버튼 클릭
       ↓
5. 로딩 스피너 + "AI가 기운을 살피는 중입니다..." 메시지 표시
       ↓
6. 분석 완료 → 화면 하단에 결과(점수 + 조언) 출력
  └─ 실패 → 에러 메시지 표시 + 버튼 재활성화
```

---

## 🏗️ 시스템 아키텍처

```
[Client (Mobile/PC 브라우저)]
   |  - UI 생성 (index.php, style.css, app.js)
   |  - 초기 로드 시 API(GET) 요청으로 잔여 횟수 표기
   | ① 파일 용량/형식 사전 유효성 검사 및 브라우저 압축 처리
   | ② FormData 비동기 전송 (fetch ➝ api.php)
   ↓
[Web Server (PHP — api.php)]
   |
   |  - 업로드 파일 백엔드 2차 유효성 검증
   |  - Rate Limiting 및 잔여 횟수 체크 (data/rate_limit.json)
   | ③ 이미지 Base64 인코딩
   | ④ JSON Payload 생성 (프롬프트 설정 + 데이터 배열화)
   | ⑤ cURL 통신 / HTTPS
   ↓
[OpenAI API Server (gpt-4o)]
   |
   | ⑥ 이미지 분석 및 결과 JSON 응답
   ↓
[Web Server (PHP — api.php)]
   |
   | ⑦ 분석 결과와 추가 데이터(잔여 횟수, 제한)를 JSON화 하여 반환
   | ⑧ API 통신 오류 시 에러 JSON 응답
   ↓
[Client (결과 화면 확인)]
   |  - 응답 JSON 데이터 파싱 및 UI 업데이트 (스코어 애니메이션 및 남은 횟수 출력)
```

---

## 📦 AI 응답 형식

AI는 항상 다음 JSON 형식으로 응답합니다:

```json
{
  "score": 85,
  "advice": [
    "동쪽 창가에 화분을 놓으면 재물운이 상승합니다.",
    "침대 머리 방향을 북쪽으로 바꾸면 숙면과 건강운에 도움이 됩니다."
  ]
}
```

---

## 🔐 보안 고려사항

- **API 키**: `.env` 파일로 분리 관리, 소스 코드에 하드코딩 절대 금지
- **파일 검증**: 클라이언트 및 서버 양쪽에서 이중 검증 수행
- **업로드 제한**: 파일 크기 최대 5MB, 이미지 형식만 허용

---

## 🗺️ 로드맵

### ✅ Phase 1 — MVP (완료)
- [x] 단일 PHP 파일 기반 구현
- [x] 모바일 최적화 UI (Bootstrap 5 + 다크 테마)
- [x] 클라이언트/서버 이중 파일 유효성 검사
- [x] 로딩 UI (스피너 + 순환 메시지)
- [x] 풍수 점수 카운트업 애니메이션
- [x] 에러 처리 및 사용자 피드백

### 🔲 Phase 2 — 고도화 (예정)
- [ ] 분석 결과 DB 저장 ('나만의 기록' 생성)
- [ ] 카카오톡 / URL 공유 기능
- [ ] AWS S3 이미지 스토리지 연동
- [ ] IP 기반 Rate Limiting 구현

---

## 📄 문서

| 문서 | 설명 |
|------|------|
| [PRD.md](docs/PRD.md) | 제품 요구사항 정의서 |
| [CHECKLIST.md](docs/CHECKLIST.md) | 개발 체크리스트 |

---

## 🤝 기여하기

1. 이 저장소를 Fork 하세요
2. Feature 브랜치를 생성하세요 (`git checkout -b feature/amazing-feature`)
3. 변경사항을 커밋하세요 (`git commit -m 'Add amazing feature'`)
4. 브랜치에 Push 하세요 (`git push origin feature/amazing-feature`)
5. Pull Request를 열어주세요

---

## 📜 라이선스

이 프로젝트는 MIT 라이선스를 따릅니다.

---

<div align="center">
  
  Made with ❤️ and ☕ | Powered by OpenAI GPT-4o

</div>
