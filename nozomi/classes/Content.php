<?php
spl_autoload_register(function ($classname) {
  require(__DIR__ . "/../classes/" . $classname . ".php");
});
class Content {
  function GetPage($rs, $app, $page) {
    if (!$page) $page = 'index';
    $conf = new Configuration();
    $config = $conf->GetConfig();
    //echo json_encode($config);
    $s = $config['sqlhost'];
    $d = $config['sqldb'];
    $u = $config['sqluser'];
    $p = $config['sqlpass'];
    $conn = new PDO("mysql:host=$s;dbname=$d", $u, $p);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $x = $conn->query("SELECT `template`,`content`,`page-title` FROM pages WHERE `name` = '$page' LIMIT 1")->fetch();

    $template = 'themes/'.$config['theme'].'/'.$x['template'];

    return $app->siteRenderer->render($rs, $template, $x);
  }
}