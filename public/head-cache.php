<?php

// head-cache.php — proxies & caches Minecraft player head textures locally
// Cached files are served with zero PHP overhead on subsequent requests
// (the front controller returns false for existing static files).

$uuid = $_GET['uuid'] ?? '';

// Normalize UUID
$uuid = strtolower(str_replace('-', '', $uuid));

if (!preg_match('/^[0-9a-f]{32}$/', $uuid)) {
    http_response_code(400);
    header('Content-Type: image/png');
    echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');
    exit;
}

$cacheDir = __DIR__ . '/images/heads';
$cacheFile = "$cacheDir/$uuid.png";

// Serve from cache if younger than 24 hours
if (is_file($cacheFile) && time() - filemtime($cacheFile) < 86400) {
    header('Content-Type: image/png');
    header('Cache-Control: public, max-age=3600');
    readfile($cacheFile);
    exit;
}

// Fetch from mc-heads.net
$ctx = stream_context_create([
    'http' => ['timeout' => 5, 'user_agent' => 'ATM10-Dashboard/1.0'],
]);
$image = @file_get_contents("https://mc-heads.net/avatar/$uuid/32", false, $ctx);

if ($image === false || strlen($image) < 100) {
    // Fallback: try minotar
    $image = @file_get_contents("https://minotar.net/avatar/$uuid/32", false, $ctx);
}

if ($image === false || strlen($image) < 100) {
    http_response_code(502);
    header('Content-Type: image/png');
    echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');
    exit;
}

// Persist to cache
@mkdir($cacheDir, 0755, true);
file_put_contents($cacheFile, $image);

header('Content-Type: image/png');
header('Cache-Control: public, max-age=3600');
echo $image;
