<?php
require 'config.php';
header('Content-Type: application/json; charset=utf-8');
if (!logged()) { http_response_code(401); echo json_encode(['error' => 'Алдымен жүйеге кіріңіз.']); exit; }
$in = json_decode(file_get_contents('php://input'), true);
$question = trim($in['question'] ?? '');
if ($question === '' || mb_strlen($question) > 1000) { echo json_encode(['error' => 'Сұрақ бос немесе тым ұзын.']); exit; }

// Платформа базасынан контекст жинау (RAG-тәрізді)
$words = array_filter(preg_split('/[^\p{L}\p{N}]+/u', mb_strtolower($question)), fn($w) => mb_strlen($w) >= 4);
$context = []; $stmt = $pdo->prepare('SELECT title, content FROM lessons WHERE LOWER(title) LIKE ? OR LOWER(content) LIKE ? LIMIT 3');
foreach ($words as $w) {
    $w = mb_substr($w, 0, max(4, mb_strlen($w) - 2)); // жалғауларды жуықтап қию
    $stmt->execute(["%$w%", "%$w%"]);
    foreach ($stmt->fetchAll() as $r) $context[$r['title']] = $r['content'];
}
$context = array_slice($context, 0, 3, true);

$answer = null;
if (ANTHROPIC_API_KEY !== '') {
    $ctx = ''; foreach ($context as $t => $c) $ctx .= "### $t\n$c\n\n";
    $system = "Сен «Дрондар әлемі» білім беру платформасының көмекшісісің. Тек қазақ тілінде, түсінікті және қысқа жауап бер. "
            . "Дрондар, ұшу қауіпсіздігі, ЖИ және дрондардың қолданысы бойынша көмектес. Тақырыптан тыс сұрақтарға сыпайы түрде дрондар тақырыбына қайтар. "
            . "Мүмкін болса төмендегі платформа материалын пайдалан.\n\n" . $ctx;
    $ch = curl_init('https://api.anthropic.com/v1/messages');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_TIMEOUT => 40,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'x-api-key: ' . ANTHROPIC_API_KEY, 'anthropic-version: 2023-06-01'],
        CURLOPT_POSTFIELDS => json_encode(['model' => ANTHROPIC_MODEL, 'max_tokens' => 700, 'system' => $system,
            'messages' => [['role' => 'user', 'content' => $question]]], JSON_UNESCAPED_UNICODE),
    ]);
    $res = json_decode(curl_exec($ch), true); curl_close($ch);
    $answer = $res['content'][0]['text'] ?? null;
}
if ($answer === null) { // API кілті жоқ немесе қате: жергілікті режим
    if ($context) { $t = array_key_first($context);
        $answer = "Платформадағы «$t» сабағынан:\n\n" . $context[$t] . "\n\n(Толығырақ жауап алу үшін әзірлеуші config.php-ге API кілтін қосуы керек.)";
    } else $answer = "Бұл сұрақ бойынша материал табылмады. Дрондардың құрылымы, түрлері, ұшу ережелері немесе ЖИ туралы сұрап көріңіз.";
}
$pdo->prepare('INSERT INTO chat_logs (user_id, question, answer) VALUES (?,?,?)')->execute([logged()['id'], $question, $answer]);
echo json_encode(['answer' => $answer], JSON_UNESCAPED_UNICODE);
