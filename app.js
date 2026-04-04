/* ══════════════════════════════════════════
   app.js — Lucky Room 클라이언트 스크립트
   담당: 업로드 UI, 이미지 압축, API 통신, 결과 렌더링
══════════════════════════════════════════ */

'use strict';

/* ── 설정 상수 (한 곳에서 관리) ── */
const CONFIG = {
  MAX_SIZE_MB:  15,                                                        // 클라이언트 검사 기준 (압축 전이라 넉넉하게)
  ALLOWED_MIME: ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
  COMPRESS:     { width: 800, height: 800, quality: 0.7 },
  API_ENDPOINT: 'api.php',
  LOADING_MESSAGES: [
    'AI가 기운을 살피는 중입니다...',
    '방의 에너지 흐름을 분석하고 있어요...',
    '풍수지리 데이터를 계산 중입니다...',
    '행운의 방향을 찾는 중이에요...',
  ],
};

/* ── DOM 참조 ── */
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
const analysisForm = document.getElementById('analysis-form');
const remainBadge  = document.getElementById('remain-badge');  // 남은 횟수 표시

/* ══════════════════════════════════════════
   에러 / 초기화 헬퍼
══════════════════════════════════════════ */

function showError(msg) {
  errorMsg.textContent = msg;
  errorArea.style.display = 'block';
  uploadZone.classList.add('error');
  btnAnalyze.disabled = true;
}

function clearError() {
  errorArea.style.display = 'none';
  uploadZone.classList.remove('error');
}

function resetUpload() {
  inputFile.value = '';
  previewImg.src  = '';
  previewWrap.style.display = 'none';
  uploadZone.style.display  = 'block';
  btnAnalyze.disabled = true;
  clearError();
}

/* ══════════════════════════════════════════
   파일 유효성 검사
══════════════════════════════════════════ */

function validateFile(file) {
  if (!file) {
    showError('사진을 먼저 선택해주세요.');
    return false;
  }
  if (!CONFIG.ALLOWED_MIME.includes(file.type)) {
    showError('이미지 파일만 업로드 가능합니다. (JPG, PNG, WEBP, GIF)');
    return false;
  }
  const maxBytes = CONFIG.MAX_SIZE_MB * 1024 * 1024;
  if (file.size > maxBytes) {
    const sizeMB = (file.size / 1024 / 1024).toFixed(1);
    showError(`파일 크기가 너무 큽니다. (${sizeMB}MB) 최대 ${CONFIG.MAX_SIZE_MB}MB 이하만 가능합니다.`);
    return false;
  }
  return true;
}

/* ══════════════════════════════════════════
   파일 선택 처리 → 미리보기
══════════════════════════════════════════ */

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

/* ══════════════════════════════════════════
   이미지 압축 (Promise)
══════════════════════════════════════════ */

function compressImage(file, maxWidth, maxHeight, quality) {
  return new Promise(resolve => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = event => {
      const img = new Image();
      img.src = event.target.result;
      img.onload = () => {
        let { width, height } = img;

        // 비율 유지 리사이즈
        if (width > height) {
          if (width > maxWidth) {
            height = Math.round((height * maxWidth) / width);
            width  = maxWidth;
          }
        } else {
          if (height > maxHeight) {
            width  = Math.round((width * maxHeight) / height);
            height = maxHeight;
          }
        }

        const canvas = document.createElement('canvas');
        canvas.width  = width;
        canvas.height = height;
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#fff'; // 투명도 대비 흰 배경
        ctx.fillRect(0, 0, width, height);
        ctx.drawImage(img, 0, 0, width, height);

        canvas.toBlob(blob => resolve(blob), 'image/jpeg', quality);
      };
    };
  });
}

/* ══════════════════════════════════════════
   로딩 UI 제어
══════════════════════════════════════════ */

let loadingTimer = null;

function showLoading() {
  clearError();
  analysisForm.style.display = 'none';
  loadingArea.style.display  = 'block';
  resultArea.style.display   = 'none';

  // 로딩 메시지 순환
  const textEl = loadingArea.querySelector('.loading-text');
  let idx = 0;
  textEl.textContent = CONFIG.LOADING_MESSAGES[0];
  loadingTimer = setInterval(() => {
    idx = (idx + 1) % CONFIG.LOADING_MESSAGES.length;
    textEl.textContent = CONFIG.LOADING_MESSAGES[idx];
  }, 2000);
}

function hideLoading() {
  clearInterval(loadingTimer);
  loadingArea.style.display  = 'none';
  analysisForm.style.display = 'block';
  btnAnalyze.disabled = false;
}

/* ══════════════════════════════════════════
   결과 렌더링 (Phase 5)
══════════════════════════════════════════ */

function getScoreEmoji(score) {
  if (score >= 85) return '🌟'; // 대길
  if (score >= 70) return '✨'; // 좋음
  if (score >= 50) return '🌿'; // 보통
  if (score >= 30) return '🔮'; // 주의
  return '🌀';                  // 걱정
}

function animateScore(targetScore) {
  const el   = document.getElementById('score-number');
  let current = 0;
  const step  = Math.ceil(targetScore / 40); // ~40 프레임
  const timer = setInterval(() => {
    current = Math.min(current + step, targetScore);
    el.textContent = current;
    if (current >= targetScore) clearInterval(timer);
  }, 30);
}

function showResult(data) {
  const { score, advice } = data;
  const scoreLabel = document.querySelector('.score-label');

  if (score === 0) {
    document.getElementById('score-number').textContent = 'X';
    scoreLabel.textContent = '⚠️ 풍수지리 분석 불가';
  } else {
    animateScore(score);
    scoreLabel.textContent = `${getScoreEmoji(score)} 풍수 에너지 지수 (100점 만점)`;
  }

  const adviceIcons = ['🌿', '💰', '🏠', '⚡', '✨'];
  advice.forEach((text, i) => {
    const advEl = document.getElementById('advice-' + (i + 1));
    if (!advEl) return;
    advEl.textContent = text;
    const iconEl = advEl.closest('.advice-item')?.querySelector('.advice-icon');
    if (iconEl) {
      iconEl.textContent = score === 0 ? '❌' : adviceIcons[i % adviceIcons.length];
    }
  });

  resultArea.style.display = 'block';
  void resultArea.offsetWidth; // 애니메이션 재시작 트리거

  // 남은 횟수 업데이트
  if (typeof data.remaining === 'number' && remainBadge) {
    const limit = data.total_limit || 5; // 기본값 5
    if (data.remaining === 0) {
      remainBadge.textContent = `오늘의 무료 분석 ${limit}회를 모두 사용했습니다. 🌙 내일 다시 오세요!`;
      remainBadge.classList.add('remain-empty');
      btnAnalyze.disabled = true;
      btnAnalyze.textContent = '😴 오늘의 분석 종료 — 내일 다시 만나요';
    } else {
      remainBadge.textContent = `오늘 남은 무료 분석 횟수: ${data.remaining}회 (총 ${limit}회)`;
    }
  }

  setTimeout(() => {
    resultArea.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }, 100);
}

/* ══════════════════════════════════════════
   이벤트 바인딩
══════════════════════════════════════════ */

// 파일 선택
inputFile.addEventListener('change', function () {
  if (this.files[0]) handleFile(this.files[0]);
});

// 미리보기 제거
clearBtn.addEventListener('click', resetUpload);

// 드래그 앤 드롭
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
  if (!file) return;
  const dt = new DataTransfer();
  dt.items.add(file);
  inputFile.files = dt.files;
  handleFile(file);
});

// 폼 submit → 압축 → API 호출 (async/await)
analysisForm.addEventListener('submit', async function (e) {
  e.preventDefault();
  const file = inputFile.files[0];
  if (!validateFile(file)) return;

  showLoading();

  try {
    const { width, height, quality } = CONFIG.COMPRESS;
    const compressedBlob = await compressImage(file, width, height, quality);

    const formData = new FormData();
    formData.append('room_image', compressedBlob, 'image.jpg');

    const res = await fetch(CONFIG.API_ENDPOINT, { method: 'POST', body: formData });
    if (!res.ok) throw new Error('HTTP ' + res.status);

    const data = await res.json();
    if (data.error) {
      showError(data.error);
      return;
    }
    showResult(data);

  } catch (err) {
    showError('네트워크 오류가 발생했습니다. 인터넷 연결을 확인해주세요.');
    console.error('[lucky-room] fetch error:', err);
  } finally {
    hideLoading();
  }
});

// 초기 로딩 시 남은 횟수 화면에 동기화
document.addEventListener('DOMContentLoaded', async () => {
  try {
    const res = await fetch(CONFIG.API_ENDPOINT);
    if (!res.ok) return;
    const data = await res.json();
    
    if (typeof data.remaining === 'number' && remainBadge) {
      const limit = data.total_limit || 5;
      if (data.remaining === 0) {
        remainBadge.textContent = `오늘의 무료 분석 ${limit}회를 모두 사용했습니다. 🌙 내일 다시 오세요!`;
        remainBadge.classList.add('remain-empty');
        btnAnalyze.disabled = true;
        btnAnalyze.textContent = '😴 오늘의 분석 종료 — 내일 다시 만나요';
      } else {
        remainBadge.textContent = `오늘 남은 무료 분석 횟수: ${data.remaining}회 (총 ${limit}회)`;
      }
    }
  } catch (err) {
    console.error('[lucky-room] initial rate limit fetch error:', err);
  }
});
