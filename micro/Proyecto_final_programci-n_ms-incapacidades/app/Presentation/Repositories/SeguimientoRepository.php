<?php

namespace App\Presentation\Repositories;

use App\Controllers\SeguimientoController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SeguimientoRepository
{
    public function historial(Request $request, Response $response, $args): Response
    {
        $incapacidad_id = (int) $args['incapacidad_id'];
        $controller = new SeguimientoController();
        $rows = $controller->obtenerHistorial($incapacidad_id);
        $response->getBody()->write($rows->toJson());
        return $response->withHeader("Content-Type", "application/json");
    }

    public function create(Request $request, Response $response, $args): Response
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);
            $controller = new SeguimientoController();
            $row = $controller->create($data);
            $response->getBody()->write($row->toJson());
            return $response->withHeader("Content-Type", "application/json");
        } catch (ModelNotFoundException $ex) {
            return $response->withStatus(406);
        }
    }

    public function update(Request $request, Response $response, $args): Response
    {
        try {
            $id = (int) $args['id'];
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);
            $controller = new SeguimientoController();
            $row = $controller->update($id, $data);
            $response->getBody()->write($row->toJson());
            return $response->withHeader("Content-Type", "application/json");
        } catch (ModelNotFoundException $ex) {
            return $response->withStatus(404);
        }
    }

    public function delete(Request $request, Response $response, $args): Response
    {
        try {
            $id = (int) $args['id'];
            $controller = new SeguimientoController();
            $row = $controller->delete($id);
            $response->getBody()->write($row->toJson());
            return $response->withHeader("Content-Type", "application/json");
        } catch (ModelNotFoundException $ex) {
            return $response->withStatus(404);
        }
    }
}
