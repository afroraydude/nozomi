<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->get('/[{name}]', function (Request $request, Response $response, array $args) {

    // Render index view
    return $this->nozomiRenderer->render($response, 'installconfirm.html', [
        'name' => $args['name']
    ]);
});