<?php

namespace App\Presentation\Repositories;

use App\Controllers\ReporteController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ReporteRepository
{
    public function resumen(Request $request, Response $response, $args): Response
    {
        $controller = new ReporteController();
        $data = $controller->resumenGeneral();
        $response->getBody()->write(json_encode($data));
        return $response->withHeader("Content-Type", "application/json");
    }

    public function porFechas(Request $request, Response $response, $args): Response
    {
        $params = $request->getQueryParams();
        $inicio = $params['fecha_inicio'] ?? date('Y-m-01');
        $fin = $params['fecha_fin'] ?? date('Y-m-t');
        $controller = new ReporteController();
        $data = $controller->reportePorFechas($inicio, $fin);
        $response->getBody()->write(json_encode($data));
        return $response->withHeader("Content-Type", "application/json");
    }
}
