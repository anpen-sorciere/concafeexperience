<?php
// debug_path_check.php - パス検出デバッグスクリプト
// Con-Cafe Princess Experience
// 注意: このファイルはデバッグ用です。使用後は削除してください。

echo "Con-Cafe Princess Experience - パス検出デバッグ\n";
echo "==========================================\n\n";

// エラー表示を有効にする
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "1. サーバー情報\n";
echo "SCRIPT_FILENAME: " . $_SERVER['SCRIPT_FILENAME'] . "\n";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "HTTP_HOST: " . $_SERVER['HTTP_HOST'] . "\n\n";

echo "2. パス計算\n";
$scriptPath = dirname($_SERVER['SCRIPT_FILENAME']);
$documentRoot = $_SERVER['DOCUMENT_ROOT'];

echo "scriptPath: " . $scriptPath . "\n";
echo "documentRoot: " . $documentRoot . "\n\n";

echo "3. プロジェクトルート検出\n";
$projectRoot = $scriptPath;

if (strpos($scriptPath, '/api/') !== false) {
    $projectRoot = dirname($scriptPath);
    echo "API ディレクトリ内から実行: " . $projectRoot . "\n";
} elseif (strpos($scriptPath, '/concafeexp/') !== false) {
    $projectRoot = $scriptPath;
    echo "concafeexp ディレクトリ内から実行: " . $projectRoot . "\n";
} else {
    $projectRoot = $documentRoot . '/concafeexp';
    echo "DOCUMENT_ROOT から計算: " . $projectRoot . "\n";
}

$projectRoot = rtrim($projectRoot, '/');
echo "最終的な projectRoot: " . $projectRoot . "\n\n";

echo "4. 各パスの確認\n";
$paths = [
    'project_root' => $projectRoot,
    'uploads' => $projectRoot . '/uploads',
    'logs' => $projectRoot . '/logs',
    'cache' => $projectRoot . '/cache',
    'tmp' => $projectRoot . '/tmp',
    'data' => $projectRoot . '/data',
    'assets' => $projectRoot . '/assets',
    'api' => $projectRoot . '/api'
];

foreach ($paths as $key => $path) {
    if (is_dir($path)) {
        echo "✅ {$key}: {$path} (存在)\n";
    } else {
        echo "❌ {$key}: {$path} (存在しない)\n";
    }
}

echo "\n5. translations.json の確認\n";
$translationsPath = $projectRoot . '/data/translations.json';
echo "translations.json パス: " . $translationsPath . "\n";

if (file_exists($translationsPath)) {
    echo "✅ translations.json が存在します\n";
    
    $translations = json_decode(file_get_contents($translationsPath), true);
    if ($translations && isset($translations['translations'])) {
        $langData = $translations['translations'];
        if (isset($langData['ja']) && isset($langData['en']) && isset($langData['zh-cn'])) {
            echo "✅ 多言語データが正常に読み込まれました\n";
            echo "対応言語: " . implode(', ', array_keys($langData)) . "\n";
        } else {
            echo "❌ 多言語データの構造が正しくありません\n";
            echo "利用可能なキー: " . implode(', ', array_keys($langData ?? [])) . "\n";
        }
    } else {
        echo "❌ 多言語データの読み込みに失敗しました\n";
        echo "JSON構造: " . json_encode(array_keys($translations ?? [])) . "\n";
    }
} else {
    echo "❌ translations.json が存在しません\n";
}

echo "\n6. ブートストラップのテスト\n";
try {
    require_once $projectRoot . '/bootstrap.php';
    echo "✅ bootstrap.php の読み込み成功\n";
    
    $bootstrapPaths = Bootstrap::getSystemInfo()['paths'];
    echo "ブートストラップ検出パス:\n";
    foreach ($bootstrapPaths as $key => $path) {
        echo "  {$key}: {$path}\n";
    }
    
} catch (Exception $e) {
    echo "❌ bootstrap.php の読み込みエラー: " . $e->getMessage() . "\n";
}

echo "\n==========================================\n";
echo "パス検出デバッグ完了\n";
echo "⚠️  このデバッグ用スクリプトは必ず削除してください！\n";
?>
