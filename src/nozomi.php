<?php

use Slim\Http\Request;
use Slim\Http\Response;

spl_autoload_register(function ($classname) {
    require(__DIR__ . "/../nozomi/classes/" . $classname . ".php");
});

$container = $app->getContainer();

//https://stackoverflow.com/questions/39559180/serving-dynamic-assets-with-slim-and-twig
//https://www.slimframework.com/docs/v3/objects/router.html#how-to-create-routes

$app->get('/nozomi/assets/{name:.*}', function (Request $request, Response $response, array $args) {
  $path = $args['name'];
  $containingFolder = __DIR__ . '/../nozomi/data/';
  $filepath = $containingFolder.$path;
  $file = @file_get_contents($filepath);
  print($file);
  $finfo = new \Finfo(FILEINFO_MIME_TYPE);
  $response->write($file);
  return $response->withHeader('Content-Type', $finfo->buffer($file));
});

$app->get('/nozomi/setup', function (Request $request, Response $response, array $args) {
  $conf = new Configuration();
  if ($conf->ConfigExists() == false) return $this->nozomiRenderer->render($response, 'setup.html');
  else return $response->withRedirect('/');
});

$app->post('/nozomi/setup', function (Request $request, Response $response, array $args) {
  $conf = new Configuration();
  if($conf->ConfigExists() == false) {
    $data = $request->getParsedBody();
    if ($conf->CreateConfiguration($data)) {
      return $response->withRedirect('/');
    } else {
      $this->nozomiRenderer->render($response, 'setup.html');
    }
  } else {
    return $response->withRedirect('/');
  }
});
