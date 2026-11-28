<?php

namespace App\Presentation\Repositories;

use App\Controllers\SeguimientoController;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SeguimientoRepository
{
    private SeguimientoController $controller;

    public function __construct()
    {
        $this->controller = new SeguimientoController();
    }

    public function obtenerPorIncapacidad(Request $request, Response $response, array $args): Response
    {
        try {
            $data = $this->controller->obtenerPorIncapacidad((int) $args['incapacidad_id']);
            return $this->jsonResponse($response, $data, 200);
        } catch (Exception $e) {
            return $this->jsonResponse($response, ['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerPorEmpleado(Request $request, Response $response, array $args): Response
    {
        try {
            $data = $this->controller->obtenerPorEmpleado((int) $args['empleado_id']);
            return $this->jsonResponse($response, $data, 200);
        } catch (Exception $e) {
            return $this->jsonResponse($response, ['error' => $e->getMessage()], 500);
        }
    }

    public function listarActividadReciente(Request $request, Response $response, array $args): Response
    {
        try {
            $params = $request->getQueryParams();
            $limite = isset($params['limite']) ? (int) $params['limite'] : 15;

            $data = $this->controller->listarActividadReciente($limite);
            return $this->jsonResponse($response, $data, 200);
        } catch (Exception $e) {
            return $this->jsonResponse($response, ['error' => $e->getMessage()], 500);
        }
    }

    public function registrarEvento(Request $request, Response $response, array $args): Response
    {
        try {
            $body = $request->getParsedBody() ?? json_decode((string) $request->getBody(), true);

            if (!is_array($body)) {
                throw new Exception("Cuerpo de la petición inválido", 400);
            }

            $seguimiento = $this->controller->registrarEvento($body);
            return $this->jsonResponse($response, $seguimiento, 201);
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
