<?php

use App\Middlewares\AuthMiddleware;
use App\Presentation\Repositories\EmpleadoRepository;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group('/api/empleados', function (RouteCollectorProxy $group) {
        
        $group->get('/documento/{numero}', [EmpleadoRepository::class, 'buscarPorDocumento']);
        
        $group->get('', [EmpleadoRepository::class, 'index']);
        $group->get('/{id}', [EmpleadoRepository::class, 'show']);
        $group->post('', [EmpleadoRepository::class, 'store']);
        $group->put('/{id}', [EmpleadoRepository::class, 'update']);
        $group->patch('/{id}/estado', [EmpleadoRepository::class, 'cambiarEstado']);
    })->add(new AuthMiddleware());
};
