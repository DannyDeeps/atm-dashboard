<?php

namespace AtmDashboard\Controllers;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;

abstract class BaseController
{
    protected \PDO $pdo;
    protected array $config;
    protected Psr17Factory $factory;
    protected string $viewsPath;
    protected string $partialsPath;

    public function __construct(\PDO $pdo, array $config)
    {
        $this->pdo = $pdo;
        $this->config = $config;
        $this->factory = new Psr17Factory;
        $this->viewsPath = __DIR__ . '/../views';
        $this->partialsPath = __DIR__ . '/../../public/partials';
    }

    protected function render(string $view, array $vars = []): ResponseInterface
    {
        $lastCollection = $this->pdo->query('SELECT MAX(collected_at) FROM player_snapshots')->fetchColumn();
        $vars['lastCollection'] = $lastCollection ? date('M j, Y g:ia', strtotime($lastCollection)) : 'Never';
        $vars['pdo'] = $this->pdo;
        $vars['config'] = $this->config;
        $vars['partialsPath'] = $this->partialsPath;
        extract($vars);

        ob_start();
        require $this->viewsPath . '/' . $view . '.php';
        $html = ob_get_clean();

        return $this->factory->createResponse(200)
            ->withHeader('Content-Type', 'text/html; charset=utf-8')
            ->withBody($this->factory->createStream($html));
    }

    protected function redirect(string $path): ResponseInterface
    {
        return $this->factory->createResponse(302)
            ->withHeader('Location', $path)
            ->withBody($this->factory->createStream(''));
    }
}
