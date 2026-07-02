<?php

namespace AtmDashboard\Controllers;

use Psr\Http\Message\ServerRequestInterface;

class MapController extends BaseController
{
    public function __invoke(ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        return $this->render('map', [
            'currentPage' => 'map',
            'headTitle' => 'Map - Glip Glops ATM10',
        ]);
    }
}
