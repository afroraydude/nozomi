<?php

// Nozomi Plugins
class MyPlugin extends \Nozomi\Core\NozomiPlugin {

  public $sidebarHTML = '<ul class="nav nav-pills flex-column">
        <li class="nav-item">
            <a class="nav-link" href="/nozomi/myplugin">My Plugin settings</a>
        </li>
    </ul>';

  public function registerRoutes() {
    $this->app->get('/nozomi/myplugin', function (\Slim\Http\Request $request, \Slim\Http\Response $response, array $args) {
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