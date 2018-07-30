<?php

use Slim\Http\Request;
use Slim\Http\Response;

$container = $app->getContainer();

//https://stackoverflow.com/questions/39559180/serving-dynamic-assets-with-slim-and-twig
//https://www.slimframework.com/docs/v3/objects/router.html#how-to-create-routes

$app->get('/nozomi/assets/{name:.*}', function (Request $request, Response $response, array $args) {
  //$settings = $container->get('settings')['nozomi'];  
  $path = $args['name'];
  //$containingFolder = $settings['data_path'];
  $containingFolder = __DIR__ . '/../nozomi/data/';
  $filepath = $containingFolder.$path;
  $file = @file_get_contents($filepath);
  $this->logger->info('FILE REQUEST '.$filepath);
  print($file);
  $finfo = new \Finfo(FILEINFO_MIME_TYPE);
  $response->write($file);
  return $response->withHeader('Content-Type', $finfo->buffer($file));
});

$app->get('/nozomi/filetest/{name:.*}', function (Request $request, Response $response, array $args) {
  return $response->write($args['name']);
});