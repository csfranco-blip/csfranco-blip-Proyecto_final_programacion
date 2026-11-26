<?php

use App\Presentation\Repositories\IncapacidadRepository;
use App\Presentation\Repositories\ReporteRepository;
use Slim\Routing\RouteCollectorProxy;

$app->group('/incapacidades', function (RouteCollectorProxy $group) {
    $group->get('', [IncapacidadRepository::class, 'all']);
    $group->get('/{id}', [IncapacidadRepository::class, 'find']);
    $group->post('', [IncapacidadRepository::class, 'create']);
    $group->put('/{id}', [IncapacidadRepository::class, 'update']);
    $group->delete('/{id}', [IncapacidadRepository::class, 'delete']);
});

$app->group('/reportes', function (RouteCollectorProxy $group) {
    $group->get('/resumen', [ReporteRepository::class, 'resumen']);
    $group->get('/fechas', [ReporteRepository::class, 'porFechas']);
});



 

