<?php

namespace App\Presentation\Routers;

use App\Presentation\Repositories\SeguimientoRepository;
use Slim\Routing\RouteCollectorProxy;

$app->group('/seguimientos', function (RouteCollectorProxy $group) {
    $group->get('/incapacidad/{incapacidad_id}', [SeguimientoRepository::class, 'historial']);
    $group->post('', [SeguimientoRepository::class, 'create']);
    $group->put('/{id}', [SeguimientoRepository::class, 'update']);
    $group->delete('/{id}', [SeguimientoRepository::class, 'delete']);
});
 

