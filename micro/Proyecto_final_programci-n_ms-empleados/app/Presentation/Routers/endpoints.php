<?php

use App\Presentation\Repositories\EmpleadoRepository;
use Slim\Routing\RouteCollectorProxy;

// Grupo de rutas para Empleados
$app->group('/empleados', function (RouteCollectorProxy $group) {
    
    $group->get('', [EmpleadoRepository::class, 'all']);
    $group->get('/documento/{documento}', [EmpleadoRepository::class, 'buscarPorDocumento']);
    $group->get('/{id}', [EmpleadoRepository::class, 'show']);
    $group->post('', [EmpleadoRepository::class, 'create']);
    $group->put('/{id}', [EmpleadoRepository::class, 'update']);
    $group->patch('/{id}/estado', [EmpleadoRepository::class, 'cambiarEstado']);
});
