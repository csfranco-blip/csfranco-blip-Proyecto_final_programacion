<?php

namespace App\Presentation\Repositories;

use App\Controllers\ControlTiempoController;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ControlTiempoRepository
{
    private ControlTiempoController $controller;

    public function __construct()
    {
        $this->controller = new ControlTiempoController();
    }

    public function calcularVencimientos(Request $request, Response $response, array $args): Response
    {
        try {
            $alertas = $this->controller->calcularVencimientos();
            return $this->jsonResponse($response, $alertas, 200);
        } catch (Exception $e) {
            $code = ($e->getCode() >= 400 && $e->getCode() < 600) ? $e->getCode() : 500;
            return $this->jsonResponse($response, ['error' => $e->getMessage()], $code);
        }
    }

    private function jsonResponse(Response $response, mixed $data, int $status): Response
    {
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
