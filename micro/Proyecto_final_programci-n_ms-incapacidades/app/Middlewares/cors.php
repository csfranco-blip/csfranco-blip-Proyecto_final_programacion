<?php

$allowedOrigins = array_values(array_filter(array_map(
    'trim',
    explode(',', getenv('CORS_ALLOWED_ORIGINS') ?: '*')
)));
$allowAnyOrigin = in_array('*', $allowedOrigins, true);

$app->add(function (\Psr\Http\Message\ServerRequestInterface $request, $handler) use ($allowedOrigins, $allowAnyOrigin) {
    $origin = $request->getHeaderLine('Origin');
    $isAllowedOrigin = $allowAnyOrigin || in_array($origin, $allowedOrigins, true);
    $response = $request->getMethod() === 'OPTIONS'
        ? new \Slim\Psr7\Response(204)
        : $handler->handle($request);

    if ($origin !== '' && $isAllowedOrigin) {
        $response = $response
            ->withHeader('Access-Control-Allow-Origin', $allowAnyOrigin ? '*' : $origin)
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->withAddedHeader('Vary', 'Origin');

        if (!$allowAnyOrigin) {
            $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');
        }
    }

    return $response;
});