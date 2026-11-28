<?php

use App\Presentation\Repositories\ControlTiempoRepository;
use App\Presentation\Repositories\SeguimientoRepository;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group('/api/seguimiento', function (RouteCollectorProxy $group) {
        $group->get('/incapacidad/{incapacidad_id}', [SeguimientoRepository::class, 'obtenerPorIncapacidad']);
        $group->get('/empleado/{empleado_id}', [SeguimientoRepository::class, 'obtenerPorEmpleado']);
        $group->get('/recientes', [SeguimientoRepository::class, 'listarActividadReciente']);
        $group->post('', [SeguimientoRepository::class, 'registrarEvento']);
        $group->get('/control-tiempos', [ControlTiempoRepository::class, 'calcularVencimientos']);
    });
};
