<?php
/* ══════════════════════════════════════════
   api.php — OpenAI API 서버 로직
   역할: POST 요청 수신 → 이미지 분석 → JSON 응답
══════════════════════════════════════════ */

// POST 요청이 아니면 접근 차단
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit('Method Not Allowed');
}

header('Content-Type: application/json; charset=utf-8');

// ── 1. .env 파일에서 환경 변수 로드
function loadEnv(string $path): array
{
  if (!file_exists($path))
    return [];

  $env = [];
  $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
    if (strpos(trim($line), '#') === 0)
      continue;
    if (strpos($line, '=') !== false) {
      [$k, $v] = explode('=', $line, 2);
      $env[trim($k)] = trim($v);
    }
  }
  return $env;
}

// ── 2. 업로드 파일 유효성 검사
function validateUpload(array $files): ?string
{
  if (!isset($files['room_image']) || $files['room_image']['error'] !== UPLOAD_ERR_OK) {
    $errCode = $files['room_image']['error'] ?? 'NO_FILE';
    if ($errCode === UPLOAD_ERR_INI_SIZE || $errCode === UPLOAD_ERR_FORM_SIZE) {
      return '사진 용량이 너무 큽니다. (서버 업로드 제한 초과) (에러코드: ' . $errCode . ')';
    }
    return '이미지 업로드에 실패했습니다. 다시 시도해주세요. (에러코드: ' . $errCode . ')';
  }

  $file = $files['room_image'];
  $mime = mime_content_type($file['tmp_name']);
  $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

  if (!in_array($mime, $allowed)) {
    return '허용되지 않는 파일 형식입니다. (JPG, PNG, WEBP, GIF만 가능)';
  }
  if ($file['size'] > 5 * 1024 * 1024) {
    return '파일 크기가 5MB를 초과합니다.';
  }

  return null; // 유효
}

// ── 3. OpenAI API 호출
function callOpenAI(string $apiKey, array $payload): array
{
  $ch = curl_init('https://api.openai.com/v1/chat/completions');
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_HTTPHEADER => [
      'Content-Type: application/json',
      'Authorization: Bearer ' . $apiKey,
    ],
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CONNECTTIMEOUT => 10,
  ]);

  $response = curl_exec($ch);
  $curlError = curl_error($ch);
  curl_close($ch);

  if ($curlError) {
    error_log('[lucky-room] cURL Error: ' . $curlError);
    return ['error' => 'API 서버 연결에 실패했습니다. 잠시 후 다시 시도해주세요.'];
  }

  $apiData = json_decode($response, true);

  if (!isset($apiData['choices'][0]['message']['content'])) {
    error_log('[lucky-room] API 오류: ' . $response);
    return ['error' => 'AI 응답을 받지 못했습니다. 다시 시도해주세요.'];
  }

  return ['content' => $apiData['choices'][0]['message']['content']];
}

// ── 4. AI 응답 파싱
function parseAIContent(string $content): array
{
  // 마크다운 코드 블록 제거 (AI가 ```json ... ``` 로 감쌀 때 대비)
  $content = preg_replace('/```json\s*/i', '', $content);
  $content = preg_replace('/```\s*/i', '', $content);
  $content = trim($content);

  $result = json_decode($content, true);

  if (!isset($result['score']) || !isset($result['advice']) || !is_array($result['advice'])) {
    error_log('[lucky-room] 파싱 실패 - content: ' . $content);
    return ['error' => '분석 결과를 처리하는 중 오류가 발생했습니다. 다시 시도해주세요.'];
  }

  return [
    'score' => max(0, min(100, (int) $result['score'])),
    'advice' => array_slice(array_values($result['advice']), 0, 2),
  ];
}

// ══════════════════════════════════════════
//  메인 처리 흐름
// ══════════════════════════════════════════

// API 키 로드
$env = loadEnv(__DIR__ . '/.env');
$apiKey = $env['OPENAI_API_KEY'] ?? '';

if (empty($apiKey)) {
  echo json_encode(['error' => 'API 키가 설정되지 않았습니다. .env 파일을 확인해주세요.']);
  exit;
}

// 파일 유효성 검사
$uploadError = validateUpload($_FILES);
if ($uploadError !== null) {
  echo json_encode(['error' => $uploadError]);
  exit;
}

// 이미지 Base64 인코딩
$file = $_FILES['room_image'];
$tmpPath = $file['tmp_name'];
$mime = mime_content_type($tmpPath);
$base64 = base64_encode(file_get_contents($tmpPath));
$dataUrl = "data:{$mime};base64,{$base64}";

// OpenAI API Payload 생성
$systemPrompt =
  '당신은 20년 경력의 한국 풍수지리 전문가입니다. ' .
  '사용자가 업로드한 방 사진을 면밀히 관찰하고, 아래 JSON 형식으로만 응답하세요. 다른 텍스트 없이 JSON만 출력하세요. ' .
  '응답 언어는 반드시 한국어로 작성하세요. ' .

  '【조언 작성 기준】 ' .
  'advice 배열에는 반드시 2개의 조언을 담으세요. ' .
  '각 조언은 다음 세 가지 요소를 모두 포함한 2~4문장 분량으로 작성하세요: ' .
  '① 사진에서 관찰된 구체적인 풍수 문제점 또는 장점 (예: 창문 방향, 가구 배치, 색상, 조명, 식물 유무 등) ' .
  '② 해당 요소가 기(氣)의 흐름·오행(五行)·음양(陰陽)에 미치는 영향을 전통 풍수 이론 관점에서 설명 ' .
  '③ 즉시 실천 가능한 구체적인 개선 방법 (무엇을, 어디에, 어떻게) ' .
  '단순히 "식물을 놓으세요" 같은 한 줄 조언은 절대 금지. 반드시 이유와 방법을 함께 서술하세요. ' .
  '톤은 전문적이되, 따뜻하고 긍정적으로 유지하세요. ' .

  '【비정상 사진 처리】 ' .
  '방이나 실내 공간이 아닌 사진(문서, 사람, 야외 풍경 등)이 업로드된 경우: ' .
  'score를 0으로 설정하고, advice[0]에 분석 불가 이유를, advice[1]에 올바른 사진 안내를 담아주세요. ' .

  '【JSON 포맷】 ' .
  '정상: {"score": (0~100 정수), "advice": ["조언1 (2~4문장)", "조언2 (2~4문장)"]} ' .
  '예외: {"score": 0, "advice": ["풍수지리 분석이 어려운 사진입니다. (이유)", "방 전체가 잘 보이는 실내 사진을 올려주시면 정확한 분석이 가능합니다."]}';

$payload = [
  'model' => 'gpt-4o',
  'max_tokens' => 4096,
  'messages' => [
    ['role' => 'system', 'content' => $systemPrompt],
    [
      'role' => 'user',
      'content' => [
        [
          'type' => 'image_url',
          'image_url' => ['url' => $dataUrl, 'detail' => 'low'],
        ],
        [
          'type' => 'text',
          'text' => '이 방의 풍수지리를 분석해주세요.',
        ],
      ],
    ],
  ],
];

// API 호출
$apiResult = callOpenAI($apiKey, $payload);
if (isset($apiResult['error'])) {
  echo json_encode($apiResult);
  exit;
}

// 응답 파싱 후 최종 출력
$output = parseAIContent($apiResult['content']);
echo json_encode($output);
