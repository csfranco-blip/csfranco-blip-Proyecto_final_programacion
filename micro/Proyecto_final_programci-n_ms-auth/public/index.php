<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/global/database_config.php';

$app = AppFactory::create();
require_once __DIR__ . '/../app/Middlewares/cors.php';

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/admin', function (Request $req, Response $res) {
    $res->getBody()->write("Zona segura");
    return $res;
})->add(function (Request $req, $handler) {
    $token = $req->getHeaderLine('Authorization');
    if ($token !== 'Bearer 12345') {
        $res = new \Slim\Psr7\Response();
        $res->getBody()->write(json_encode(['error' => 'No autorizado']));
        return $res->withStatus(401)->withHeader('Content-Type', 'application/json');
    }
    return $handler->handle($req);
});

$app->run();
