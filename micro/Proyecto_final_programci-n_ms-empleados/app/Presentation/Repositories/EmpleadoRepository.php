<?php

namespace App\Presentation\Repositories;

use App\Controllers\EmpleadoController;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EmpleadoRepository
{

    public function all(Request $request, Response $response, $args): Response
    {
        $params = $request->getQueryParams();
        $estado = $params['estado'] ?? null;
        $area = $params['area'] ?? null;

        $controller = new EmpleadoController();
        $rows = $controller->index($estado, $area);
        $response->getBody()->write($rows->toJson());
        return $response->withHeader("Content-Type", "application/json");
    }

    public function show(Request $request, Response $response, $args): Response
    {
        try {
            $id = (int) $args['id'];
            $controller = new EmpleadoController();
            $row = $controller->show($id);
            $response->getBody()->write($row->toJson());
            return $response->withHeader("Content-Type", "application/json");
        } catch (ModelNotFoundException $ex) {
            return $response->withStatus(404);
        }
    }

    public function buscarPorDocumento(Request $request, Response $response, $args): Response
    {
        try {
            $documento = $args['documento'];
            $controller = new EmpleadoController();
            $row = $controller->buscarPorDocumento($documento);
            $response->getBody()->write($row->toJson());
            return $response->withHeader("Content-Type", "application/json");
        } catch (ModelNotFoundException $ex) {
            return $response->withStatus(404);
        }
    }

    public function create(Request $request, Response $response, $args): Response
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);
            $controller = new EmpleadoController();
            $row = $controller->store($data);
            $response->getBody()->write($row->toJson());
            return $response->withHeader("Content-Type", "application/json");
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode(['error' => $ex->getMessage()]));
            return $response->withStatus($ex->getCode() ?: 400)->withHeader("Content-Type", "application/json");
        }
    }

    public function update(Request $request, Response $response, $args): Response
    {
        try {
            $id = (int) $args['id'];
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);
            $controller = new EmpleadoController();
            $row = $controller->update($id, $data);
            $response->getBody()->write($row->toJson());
            return $response->withHeader("Content-Type", "application/json");
        } catch (ModelNotFoundException $ex) {
            return $response->withStatus(404);
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode(['error' => $ex->getMessage()]));
            return $response->withStatus($ex->getCode() ?: 400)->withHeader("Content-Type", "application/json");
        }
    }

    public function cambiarEstado(Request $request, Response $response, $args): Response
    {
        try {
            $id = (int) $args['id'];
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);
            $estado = $data['estado'] ?? null;

            $controller = new EmpleadoController();
            $row = $controller->cambiarEstado($id, $estado);
            $response->getBody()->write($row->toJson());
            return $response->withHeader("Content-Type", "application/json");
        } catch (ModelNotFoundException $ex) {
            return $response->withStatus(404);
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode(['error' => $ex->getMessage()]));
            return $response->withStatus($ex->getCode() ?: 400)->withHeader("Content-Type", "application/json");
        }
    }
}
