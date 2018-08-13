<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
  $settings = $c->get('settings')['renderer'];
  return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
  $settings = $c->get('settings')['logger'];
  $logger = new Monolog\Logger($settings['name']);
  $logger->pushProcessor(new Monolog\Processor\UidProcessor());
  $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
  return $logger;
};

// Nozomi Plugins
class MyPlugin extends \Nozomi\Core\NozomiPlugin {
  public function registerRoutes() {
    $this->app->get('/myplugin', function (\Slim\Http\Request $request, \Slim\Http\Response $response, array $args) {
      return $response->getBody()->write("My plugin is working!");
    });
  }
}

// Register plugin
$pluginHandler = new \Nozomi\Core\NozomiPluginHandler();
$plugin = new MyPlugin($app);
$blogPlugin = new \Nozomi\Blog\NozomiBlog($app);
$pluginHandler->registerPlugin($plugin);
$pluginHandler->registerPlugin($blogPlugin);