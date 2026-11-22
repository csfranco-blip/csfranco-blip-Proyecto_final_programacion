<?php

namespace App\Presentation\Repositories;

use App\Controllers\TokenController;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TokenRepository
{
    public function all(Request $request, Response $response, $args): Response
    {
        $controller = new TokenController();
        $rows = $controller->get_tokens();
        $response->getBody()->write($rows->toJson());
        return $response->withHeader("Content-Type", "application/json");
    }

    public function find(Request $request, Response $response, $args): Response
    {
        try {
            $id = (int) $args['id'];
            $controller = new TokenController();
            $row = $controller->find_token($id);
            $response->getBody()->write($row->toJson());
            return $response->withHeader("Content-Type", "application/json");
        } catch (ModelNotFoundException $ex) {
            return $response->withStatus(404);
        }
    }

    public function byUser(Request $request, Response $response, $args): Response
    {
        try {
            $userId = (int) $args['user_id'];
            $controller = new TokenController();
            $rows = $controller->get_by_user($userId);
            $response->getBody()->write($rows->toJson());
            return $response->withHeader("Content-Type", "application/json");
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode(["error" => $ex->getMessage()]));
            return $response
                ->withHeader("Content-Type", "application/json")
                ->withStatus(400);
        }
    }

    public function revoke(Request $request, Response $response, $args): Response
    {
        try {
            $id = (int) $args['id'];
            $controller = new TokenController();
            $row = $controller->revoke($id);
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
            $controller = new TokenController();
            $row = $controller->delete($id);
            $response->getBody()->write($row->toJson());
            return $response->withHeader("Content-Type", "application/json");
        } catch (ModelNotFoundException $ex) {
            return $response->withStatus(404);
        }
    }
}
