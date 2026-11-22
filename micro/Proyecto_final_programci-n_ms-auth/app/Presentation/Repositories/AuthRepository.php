<?php

namespace App\Presentation\Repositories;

use App\Controllers\AuthController;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthRepository
{
    public function login(Request $request, Response $response, $args): Response
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);

            $controller = new AuthController();
            $row = $controller->login($data['correo'], $data['password']);

            $response->getBody()->write(json_encode($row));
            return $response->withHeader("Content-Type", "application/json");
        } catch (ModelNotFoundException $ex) {
            return $response->withStatus(401);
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode(["error" => $ex->getMessage()]));
            return $response
                ->withHeader("Content-Type", "application/json")
                ->withStatus($ex->getCode() ?: 400);
        }
    }

    public function logout(Request $request, Response $response, $args): Response
    {
        try {
            $authHeader = $request->getHeaderLine("Authorization");
            $token = str_replace("Bearer ", "", $authHeader);

            $controller = new AuthController();
            $controller->logout($token);

            $response->getBody()->write(json_encode(["mensaje" => "Sesión cerrada correctamente"]));
            return $response->withHeader("Content-Type", "application/json");
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode(["error" => $ex->getMessage()]));
            return $response
                ->withHeader("Content-Type", "application/json")
                ->withStatus($ex->getCode() ?: 400);
        }
    }

    public function validate(Request $request, Response $response, $args): Response
    {
        try {
            $authHeader = $request->getHeaderLine("Authorization");
            $token = str_replace("Bearer ", "", $authHeader);

            $controller = new AuthController();
            $row = $controller->validarToken($token);

            $response->getBody()->write(json_encode($row));
            return $response->withHeader("Content-Type", "application/json");
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode(["valido" => false, "error" => $ex->getMessage()]));
            return $response
                ->withHeader("Content-Type", "application/json")
                ->withStatus(401);
        }
    }

    public function register(Request $request, Response $response, $args): Response
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);

            $controller = new AuthController();
            $row = $controller->register($data);

            $response->getBody()->write($row->toJson());
            return $response
                ->withHeader("Content-Type", "application/json")
                ->withStatus(201);
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode(["error" => $ex->getMessage()]));
            return $response
                ->withHeader("Content-Type", "application/json")
                ->withStatus($ex->getCode() ?: 406);
        }
    }
}
