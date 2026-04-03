<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="description" content="방 사진 한 장으로 AI가 풍수지리를 분석해드립니다. 재물운·건강운 인테리어 조언을 받아보세요.">
  <meta name="theme-color" content="#1a1a2e">

  <title>🏮 AI 풍수지리 방 분석기 | Lucky Room</title>

  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+KR:wght@400;600;700&family=Noto+Sans+KR:wght@300;400;500&display=swap" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --color-bg:        #0f0e17;
      --color-surface:   #1a1830;
      --color-card:      #22203a;
      --color-gold:      #c9a84c;
      --color-gold-light:#e8c96b;
      --color-red:       #c0392b;
      --color-green:     #27ae60;
      --color-text:      #e8e6f0;
      --color-muted:     #8b89a0;
      --color-border:    rgba(201,168,76,0.2);
    }

    * { box-sizing: border-box; }

    body {
      background-color: var(--color-bg);
      color: var(--color-text);
      font-family: 'Noto Sans KR', sans-serif;
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* ── 배경 패턴 ── */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background:
        radial-gradient(ellipse 80% 60% at 50% -10%, rgba(201,168,76,0.12) 0%, transparent 70%),
        radial-gradient(ellipse 60% 40% at 80% 80%,  rgba(192,57,43,0.06)  0%, transparent 60%);
      pointer-events: none;
      z-index: 0;
    }

    .page-wrap {
      position: relative;
      z-index: 1;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ── 헤더 ── */
    .site-header {
      padding: 2rem 1rem 1rem;
      text-align: center;
    }

    .site-header .badge-tag {
      display: inline-block;
      font-size: 0.7rem;
      letter-spacing: 0.15em;
      text-transform: uppercase;
      color: var(--color-gold);
      border: 1px solid var(--color-gold);
      padding: 0.25rem 0.75rem;
      border-radius: 999px;
      margin-bottom: 1rem;
    }

    .site-title {
      font-family: 'Noto Serif KR', serif;
      font-size: clamp(1.6rem, 6vw, 2.4rem);
      font-weight: 700;
      background: linear-gradient(135deg, var(--color-gold-light) 0%, var(--color-gold) 60%, #a07830 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      line-height: 1.3;
      margin-bottom: 0.5rem;
    }

    .site-subtitle {
      font-size: 0.9rem;
      color: var(--color-muted);
      line-height: 1.6;
    }

    /* ── 메인 카드 ── */
    .main-card {
      background: var(--color-card);
      border: 1px solid var(--color-border);
      border-radius: 1.5rem;
      padding: 2rem 1.5rem;
      box-shadow: 0 8px 40px rgba(0,0,0,0.4), 0 0 0 1px rgba(201,168,76,0.05);
    }

    /* ── 구분선 ── */
    .divider {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin: 1.5rem 0;
      color: var(--color-muted);
      font-size: 0.75rem;
    }
    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--color-border);
    }

    /* ── 업로드 영역 ── */
    .upload-zone {
      border: 2px dashed var(--color-border);
      border-radius: 1.25rem;
      padding: 2.5rem 1rem;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s ease;
      background: rgba(201,168,76,0.03);
      position: relative;
      overflow: hidden;
    }

    .upload-zone:hover,
    .upload-zone.dragover {
      border-color: var(--color-gold);
      background: rgba(201,168,76,0.07);
      transform: translateY(-2px);
    }

    .upload-zone .upload-icon {
      font-size: 3rem;
      color: var(--color-gold);
      margin-bottom: 0.75rem;
      display: block;
      line-height: 1;
    }

    .upload-zone .upload-title {
      font-family: 'Noto Serif KR', serif;
      font-size: 1.05rem;
      color: var(--color-text);
      margin-bottom: 0.25rem;
    }

    .upload-zone .upload-hint {
      font-size: 0.78rem;
      color: var(--color-muted);
    }

    /* ── 미리보기 이미지 ── */
    #preview-wrap {
      display: none;
      margin-top: 1rem;
      border-radius: 1rem;
      overflow: hidden;
      border: 1px solid var(--color-border);
      position: relative;
    }

    #preview-img {
      width: 100%;
      max-height: 260px;
      object-fit: cover;
      display: block;
    }

    #preview-clear {
      position: absolute;
      top: 0.5rem;
      right: 0.5rem;
      background: rgba(0,0,0,0.6);
      border: none;
      color: #fff;
      border-radius: 50%;
      width: 2rem;
      height: 2rem;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 1rem;
    }

    /* ── 분석 버튼 ── */
    .btn-analyze {
      width: 100%;
      padding: 1rem;
      border-radius: 1rem;
      font-family: 'Noto Serif KR', serif;
      font-size: 1.05rem;
      font-weight: 600;
      letter-spacing: 0.05em;
      background: linear-gradient(135deg, #b8963e 0%, var(--color-gold) 50%, #d4af5a 100%);
      border: none;
      color: #1a1205;
      transition: all 0.3s ease;
      box-shadow: 0 4px 20px rgba(201,168,76,0.3);
    }

    .btn-analyze:hover:not(:disabled) {
      transform: translateY(-2px);
      box-shadow: 0 6px 28px rgba(201,168,76,0.45);
    }

    .btn-analyze:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    /* ── 로딩 스피너 ── */
    #loading-area {
      display: none;
      text-align: center;
      padding: 1.5rem 0;
    }

    .loading-spinner {
      width: 3rem;
      height: 3rem;
      border: 3px solid rgba(201,168,76,0.15);
      border-top-color: var(--color-gold);
      border-radius: 50%;
      animation: spin 0.9s linear infinite;
      margin: 0 auto 1rem;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .loading-text {
      font-size: 0.9rem;
      color: var(--color-gold);
      animation: pulse 1.8s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% { opacity: 1; }
      50%       { opacity: 0.5; }
    }

    /* ── 결과 영역 (Phase 5에서 내용 채울 예정) ── */
    #result-area {
      display: none;
      margin-top: 1.5rem;
    }

    .result-card {
      background: var(--color-surface);
      border: 1px solid var(--color-border);
      border-radius: 1.25rem;
      padding: 1.5rem;
      margin-bottom: 1rem;
    }

    .score-ring-wrap {
      text-align: center;
      padding: 1rem 0 0.5rem;
    }

    .score-number {
      font-family: 'Noto Serif KR', serif;
      font-size: 3.5rem;
      font-weight: 700;
      background: linear-gradient(135deg, var(--color-gold-light), var(--color-gold));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      line-height: 1;
    }

    .score-label {
      font-size: 0.78rem;
      color: var(--color-muted);
      margin-top: 0.25rem;
    }

    .advice-item {
      display: flex;
      gap: 0.75rem;
      padding: 1rem;
      background: rgba(201,168,76,0.05);
      border-radius: 0.75rem;
      margin-bottom: 0.75rem;
      border-left: 3px solid var(--color-gold);
    }

    .advice-item .advice-icon {
      font-size: 1.3rem;
      flex-shrink: 0;
      margin-top: 0.1rem;
    }

    .advice-text {
      font-size: 0.9rem;
      line-height: 1.7;
      color: var(--color-text);
      white-space: pre-wrap;
    }

    /* ── 에러 메시지 ── */
    #error-area {
      display: none;
      background: rgba(192,57,43,0.1);
      border: 1px solid rgba(192,57,43,0.3);
      border-radius: 0.75rem;
      padding: 1rem;
      margin-top: 1rem;
      font-size: 0.88rem;
      color: #e74c3c;
      animation: fadeIn 0.3s ease;
    }

    /* ── 업로드 에러 상태 ── */
    .upload-zone.error {
      border-color: rgba(192,57,43,0.6);
      background: rgba(192,57,43,0.05);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-4px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* ── 푸터 ── */
    .site-footer {
      text-align: center;
      padding: 1.5rem 1rem 2rem;
      font-size: 0.75rem;
      color: var(--color-muted);
    }

    .site-footer .fortune-tip {
      display: inline-block;
      margin-top: 0.5rem;
      padding: 0.4rem 1rem;
      border: 1px solid var(--color-border);
      border-radius: 999px;
      font-size: 0.72rem;
      color: var(--color-gold);
    }
  </style>
</head>
<body>
<div class="page-wrap">

  <!-- ── 헤더 ── -->
  <header class="site-header">
    <div class="badge-tag">✦ AI 풍수지리 ✦</div>
    <h1 class="site-title">방 한 장으로<br>운기를 읽다</h1>
    <p class="site-subtitle">
      사진 한 장을 올리면 AI가<br>
      풍수지리 점수와 인테리어 운세를 분석해드립니다
    </p>
  </header>

  <!-- ── 메인 콘텐츠 ── -->
  <main class="container" style="max-width:480px;">
    <div class="main-card">

      <!-- 업로드 폼 -->
      <form id="analysis-form" enctype="multipart/form-data" method="post">

        <!-- 업로드 영역 -->
        <div class="upload-zone" id="upload-zone">
          <span class="upload-icon">🏮</span>
          <p class="upload-title">방 사진을 선택하세요</p>
          <p class="upload-hint">
            카메라 촬영 또는 갤러리에서 선택<br>
            <small>최대 5MB · JPG, PNG, WEBP</small>
          </p>
          <input type="file"
                 id="room-image"
                 name="room_image"
                 accept="image/jpeg,image/png,image/webp,image/gif"
                 style="position:absolute;inset:0;opacity:0;cursor:pointer;"
                 aria-label="방 사진 업로드">
        </div>

        <!-- 이미지 미리보기 -->
        <div id="preview-wrap">
          <img id="preview-img" src="" alt="업로드된 방 사진 미리보기">
          <button type="button" id="preview-clear" title="사진 제거">
            <i class="bi bi-x"></i>
          </button>
        </div>

        <div class="divider">기운을 분석합니다</div>

        <!-- 분석 버튼 -->
        <button type="submit" id="btn-analyze" class="btn-analyze" disabled>
          <i class="bi bi-stars"></i>&nbsp; 운기 분석 시작하기
        </button>

      </form>

      <!-- 로딩 -->
      <div id="loading-area">
        <div class="loading-spinner"></div>
        <p class="loading-text">AI가 기운을 살피는 중입니다...</p>
      </div>

      <!-- 에러 메시지 -->
      <div id="error-area" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span id="error-msg">분석 중 오류가 발생했습니다. 다시 시도해주세요.</span>
      </div>

      <!-- 결과 영역 -->
      <div id="result-area">
        <div class="divider">분석 결과</div>

        <!-- 풍수 점수 -->
        <div class="result-card">
          <div class="score-ring-wrap">
            <div class="score-number" id="score-number">--</div>
            <div class="score-label">풍수 에너지 지수 (100점 만점)</div>
          </div>
        </div>

        <!-- 인테리어 조언 -->
        <div id="advice-list">
          <div class="advice-item">
            <span class="advice-icon">🌿</span>
            <p class="advice-text" id="advice-1">조언을 불러오는 중...</p>
          </div>
          <div class="advice-item">
            <span class="advice-icon">💰</span>
            <p class="advice-text" id="advice-2">조언을 불러오는 중...</p>
          </div>
        </div>
      </div>

    </div><!-- /.main-card -->
  </main>

  <!-- ── 푸터 ── -->
  <footer class="site-footer">
    <p>© 2026 Lucky Room · AI 풍수지리 분석</p>
    <span class="fortune-tip">✦ 재미로 즐기는 AI 운세 · 참고용으로만 활용하세요 ✦</span>
  </footer>

</div><!-- /.page-wrap -->

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* ══════════════════════════════════════════
   Phase 2: 이미지 업로드 유효성 검사
   Phase 3: 로딩 UI 제어
══════════════════════════════════════════ */
const inputFile    = document.getElementById('room-image');
const uploadZone   = document.getElementById('upload-zone');
const previewWrap  = document.getElementById('preview-wrap');
const previewImg   = document.getElementById('preview-img');
const btnAnalyze   = document.getElementById('btn-analyze');
const clearBtn     = document.getElementById('preview-clear');
const errorArea    = document.getElementById('error-area');
const errorMsg     = document.getElementById('error-msg');
const loadingArea  = document.getElementById('loading-area');
const resultArea   = document.getElementById('result-area');

const MAX_SIZE_MB  = 5;
const MAX_SIZE_B   = MAX_SIZE_MB * 1024 * 1024;
const ALLOWED_MIME = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

/** 에러 표시 */
function showError(msg) {
  errorMsg.textContent = msg;
  errorArea.style.display = 'block';
  uploadZone.classList.add('error');
  btnAnalyze.disabled = true;
}

/** 에러 초기화 */
function clearError() {
  errorArea.style.display = 'none';
  uploadZone.classList.remove('error');
}

/** 미리보기 초기화 */
function resetUpload() {
  inputFile.value = '';
  previewImg.src  = '';
  previewWrap.style.display = 'none';
  uploadZone.style.display  = 'block';
  btnAnalyze.disabled = true;
  clearError();
}

/** 파일 유효성 검사 */
function validateFile(file) {
  if (!file) {
    showError('사진을 먼저 선택해주세요.');
    return false;
  }
  if (!ALLOWED_MIME.includes(file.type)) {
    showError('이미지 파일만 업로드 가능합니다. (JPG, PNG, WEBP, GIF)');
    return false;
  }
  if (file.size > MAX_SIZE_B) {
    const sizeMB = (file.size / 1024 / 1024).toFixed(1);
    showError(`파일 크기가 너무 큽니다. (${sizeMB}MB) 최대 ${MAX_SIZE_MB}MB 이하만 가능합니다.`);
    return false;
  }
  return true;
}

/** 파일 선택 → 유효성 검사 → 미리보기 */
function handleFile(file) {
  clearError();
  if (!validateFile(file)) {
    resetUpload();
    return;
  }

  const reader = new FileReader();
  reader.onload = e => {
    previewImg.src = e.target.result;
    previewWrap.style.display = 'block';
    uploadZone.style.display  = 'none';
    btnAnalyze.disabled = false;
  };
  reader.readAsDataURL(file);
}

// ── 파일 input change 이벤트
inputFile.addEventListener('change', function () {
  const file = this.files[0];
  if (file) handleFile(file);
});

// ── 미리보기 제거 버튼
clearBtn.addEventListener('click', resetUpload);

// ── 드래그 앤 드롭
uploadZone.addEventListener('dragover', e => {
  e.preventDefault();
  uploadZone.classList.add('dragover');
});
uploadZone.addEventListener('dragleave', () => {
  uploadZone.classList.remove('dragover');
});
uploadZone.addEventListener('drop', e => {
  e.preventDefault();
  uploadZone.classList.remove('dragover');
  const file = e.dataTransfer.files[0];
  if (file) {
    // DataTransfer로 받은 파일을 input에도 세팅
    const dt = new DataTransfer();
    dt.items.add(file);
    inputFile.files = dt.files;
    handleFile(file);
  }
});

// ── 폼 submit 최종 검사 (Phase 3~4에서 실제 전송 로직 추가 예정)
document.getElementById('analysis-form').addEventListener('submit', function (e) {
  e.preventDefault();
  const file = inputFile.files[0];
  if (!validateFile(file)) return;

  // ── Phase 3: 로딩 시작
  showLoading();
  // TODO Phase 4: API 호출 후 hideLoading() 호출 예정
});

/* ══════════════════════════════════════════
   Phase 3: 로딩 UI 제어 함수
══════════════════════════════════════════ */

// 로딩 메시지 순환 (지루하지 않게 😄)
const LOADING_MESSAGES = [
  'AI가 기운을 살피는 중입니다...',
  '방의 에너지 흐름을 분석하고 있어요...',
  '풍수지리 데이터를 계산 중입니다...',
  '행운의 방향을 찾는 중이에요...',
];
let loadingTimer = null;

function showLoading() {
  clearError();
  // 폼 숨기고 로딩 표시
  document.getElementById('analysis-form').style.display = 'none';
  loadingArea.style.display = 'block';
  resultArea.style.display  = 'none';

  // 로딩 메시지 순환
  let idx = 0;
  const textEl = loadingArea.querySelector('.loading-text');
  textEl.textContent = LOADING_MESSAGES[0];
  loadingTimer = setInterval(() => {
    idx = (idx + 1) % LOADING_MESSAGES.length;
    textEl.textContent = LOADING_MESSAGES[idx];
  }, 2000);
}

function hideLoading() {
  clearInterval(loadingTimer);
  loadingArea.style.display = 'none';
  document.getElementById('analysis-form').style.display = 'block';
  btnAnalyze.disabled = false;
}
</script>

</body>
</html>
