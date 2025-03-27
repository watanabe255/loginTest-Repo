<?php
session_start();
require_once 'vendor/autoload.php';

// ✅ 環境変数（.env）からクライアントIDを取得する（セキュリティ向上）
function get_env_variable($key) {
    $lines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        list($envKey, $envValue) = explode('=', $line, 2);
        if (trim($envKey) === $key) {
            return trim($envValue);
        }
    }
    return null;
}

$client_id = get_env_variable('GOOGLE_CLIENT_ID');

// ✅ クライアントIDが取得できなかった場合の処理
if (!$client_id) {
    http_response_code(500);
    echo json_encode(["error" => "Client ID not set in .env"]);
    exit();
}

// ✅ POSTデータの取得
$id_token = filter_input(INPUT_POST, 'idtoken', FILTER_SANITIZE_STRING);

if (!$id_token) {
    http_response_code(400);
    echo json_encode(["error" => "ID Token is missing"]);
    exit();
}

// ✅ Googleのクライアント設定
$client = new Google_Client(['client_id' => $client_id]); 
$payload = $client->verifyIdToken($id_token);

if ($payload) {
    $userid = $payload['sub']; // GoogleのユーザーID

    // ✅ セッションにユーザー情報を保存
    $_SESSION['login'] = true;
    $_SESSION['user_id'] = $userid;
    $_SESSION['email'] = $payload['email'];

    // ✅ JSONレスポンスを返す
    echo json_encode(["status" => "success", "user_id" => $userid, "email" => $payload['email']]);
    exit();
} else {
    http_response_code(401);
    echo json_encode(["error" => "Invalid ID Token"]);
    exit();
}
