<?php

use App\Presentation\Repositories\AuthRepository;
use App\Presentation\Repositories\TokenRepository;
use Slim\App;

return function (App $app) {
    $app->post('/api/auth/login', [AuthRepository::class, 'login']);
    $app->post('/api/auth/logout', [AuthRepository::class, 'logout']);
    $app->get('/api/auth/validate', [AuthRepository::class, 'validate']);
    $app->post('/api/auth/register', [AuthRepository::class, 'register']);
    
    $app->get('/api/tokens', [TokenRepository::class, 'all']);
    $app->get('/api/tokens/{id}', [TokenRepository::class, 'find']);
    $app->get('/api/tokens/usuario/{user_id}', [TokenRepository::class, 'byUser']);
    $app->put('/api/tokens/{id}/revoke', [TokenRepository::class, 'revoke']);
    $app->delete('/api/tokens/{id}', [TokenRepository::class, 'delete']);

};
