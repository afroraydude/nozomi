<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
  $settings = $c->get('settings')['renderer'];
  return new Slim\Views\PhpRenderer($settings['template_path']);
};

$container['nozomiConsolePhp'] = function ($c) {
  $settings = $c->get('settings')['nozomi'];
  return new Slim\Views\PhpRenderer($settings['pages_path']);
};

$container['nozomiRenderer'] = function ($container) {
  $settings = $container->get('settings')['nozomi'];
  $view = new \Slim\Views\Twig($settings['pages_path'], [
    'cache' => $settings['cache_path']
  ]);

  // Instantiate and add Slim specific extension
  $url = rtrim(str_ireplace('index.php', '', $container->get('request')->getUri()->getPath()), '/');
  $view->addExtension(new Slim\Views\TwigExtension($container->get('router'), $url));
  return $view;
};

$container['siteRenderer'] = function ($container) {
  $settings = $container->get('settings')['nozomi'];
  $view = new \Slim\Views\Twig($settings['site_path'], [
    'cache' => $settings['cache_path']
  ]);

  // Instantiate and add Slim specific extension
  $basePath = rtrim(str_ireplace('index.php', '', $container->get('request')->getUri()->getBasePath()), '/');
  $view->addExtension(new Slim\Views\TwigExtension($container->get('router'), $basePath));

  return $view;
};

// monolog
$container['logger'] = function ($c) {
  $settings = $c->get('settings')['logger'];
  $logger = new Monolog\Logger($settings['name']);
  $logger->pushProcessor(new Monolog\Processor\UidProcessor());
  $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
  return $logger;
};
