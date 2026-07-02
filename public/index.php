<?php

// Let the PHP built-in server serve static files directly
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($uri !== '/' && is_file(__DIR__ . $uri)) {
    return false;
}

require_once __DIR__ . '/../vendor/autoload.php';

use League\Route\Http\Exception\NotFoundException;
use League\Route\Router;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use AtmDashboard\Controllers\HomeController;
use AtmDashboard\Controllers\PlayersController;
use AtmDashboard\Controllers\PlayerController;
use AtmDashboard\Controllers\BossesController;
use AtmDashboard\Controllers\MapController;
use AtmDashboard\Controllers\ArmoryController;

$config = require __DIR__ . '/../config.php';
$dbConfig = $config['database_config'] ?? $config['database'];
$db = new AtmDashboard\Database($dbConfig);
$pdo = $db->pdo();
$factory = new Psr17Factory;

$router = new Router;

$router->get('/', new HomeController($pdo, $config));
$router->get('/players', new PlayersController($pdo, $config));
$router->get('/player/{uuid}', new PlayerController($pdo, $config));
$router->get('/bosses', new BossesController($pdo, $config));
$router->get('/armory', new ArmoryController($pdo, $config));
$router->get('/map', new MapController($pdo, $config));
$router->get('/map/', function () use ($factory) {
    return $factory->createResponse(302)->withHeader('Location', '/map')->withBody($factory->createStream(''));
});

// ── Landing page mockups ──
require __DIR__ . '/mockups.php';

// Redirect legacy .php URLs to clean paths
$router->get('/index.php', function () use ($factory) {
    return $factory->createResponse(302)->withHeader('Location', '/')->withBody($factory->createStream(''));
});
$router->get('/players.php', function () use ($factory) {
    return $factory->createResponse(302)->withHeader('Location', '/players')->withBody($factory->createStream(''));
});
$router->get('/bosses.php', function () use ($factory) {
    return $factory->createResponse(302)->withHeader('Location', '/bosses')->withBody($factory->createStream(''));
});

$errorPage = function (int $code, string $message) use ($factory): \Psr\Http\Message\ResponseInterface {
    $body = '<!DOCTYPE html><html lang="en" data-theme="dark"><head><meta charset="UTF-8"><title>' . $code . '</title>'
        . '<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>'
        . '<link href="https://cdn.jsdelivr.net/npm/daisyui@5.0.0/themes.css" rel="stylesheet">'
        . '</head><body class="flex items-center justify-center min-h-screen bg-base-300">'
        . '<div class="text-center"><h1 class="text-6xl font-extrabold text-primary">' . $code . '</h1>'
        . '<p class="text-lg text-base-content/60 mt-2">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>'
        . '<a href="/" class="btn btn-primary mt-4">Go Home</a></div></body></html>';
    return $factory->createResponse($code)
        ->withHeader('Content-Type', 'text/html; charset=utf-8')
        ->withBody($factory->createStream($body));
};

$request = (new ServerRequestCreator($factory, $factory, $factory, $factory))->fromGlobals();

try {
    $response = $router->dispatch($request);
} catch (NotFoundException $e) {
    $response = $errorPage(404, 'Not Found');
} catch (\Throwable $e) {
    $response = $errorPage(500, 'Server Error');
}

http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $name => $values) {
    foreach ($values as $value) {
        header("$name: $value", false);
    }
}
echo $response->getBody();
