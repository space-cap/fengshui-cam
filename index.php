<!DOCTYPE html>
<html lang="ko">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="description" content="방 사진 한 장으로 AI가 풍수지리를 분석해드립니다. 재물운·건강운 인테리어 조언을 받아보세요.">
  <meta name="theme-color" content="#1a1a2e">

  <title>🏮 AI 풍수지리 방 분석기 | Lucky Room</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+KR:wght@400;600;700&family=Noto+Sans+KR:wght@300;400;500&display=swap" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <!-- 앱 스타일시트 -->
  <link href="style.css" rel="stylesheet">
</head>

<body>
  <div class="page-wrap">

    <!-- 헤더 -->
    <header class="site-header">
      <div class="badge-tag">✦ AI 풍수지리 ✦</div>
      <h1 class="site-title">방 한 장으로<br>운기를 읽다</h1>
      <p class="site-subtitle">
        사진 한 장을 올리면 AI가<br>
        풍수지리 점수와 인테리어 운세를 분석해드립니다
      </p>
      <!-- 남은 횟수 뱃지 (분석 완료 후 JS로 업데이트) -->
      <span id="remain-badge" class="remain-badge">오늘 무료 분석 5회 제공</span>
    </header>

    <!-- 메인 콘텐츠 -->
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
            <input type="file" id="room-image" name="room_image"
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

    <!-- 푸터 -->
    <footer class="site-footer">
      <p>© 2026 Lucky Room · AI 풍수지리 분석</p>
      <span class="fortune-tip">✦ 재미로 즐기는 AI 운세 · 참고용으로만 활용하세요 ✦</span>
    </footer>

  </div><!-- /.page-wrap -->

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- 앱 스크립트 -->
  <script src="app.js"></script>

</body>

</html>