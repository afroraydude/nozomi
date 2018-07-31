<?php
class Configuration {

  function GetConfig() {
    $configLocation = __DIR__ . '/../../site/config.ini';
    $config = parse_ini_file($configLocation, true);
    return $config;
  }

  function ConfigExists() {
    $configLocation = __DIR__ . '/../../site/config.ini';
    $config = parse_ini_file($configLocation, true);
    if ($config) return true;
    else return false;
  }

  function put_ini_file($config, $file, $has_section = false, $write_to_file = true){
    $fileContent = '';
    if(!empty($config)){
      foreach($config as $i=>$v){
        if($has_section){
          $fileContent .= "[".$i."]\n\r" . put_ini_file($v, $file, false, false);
        }
        else{
          if(is_array($v)){
            foreach($v as $t=>$m){
              $fileContent .= $i."[".$t."] = ".(is_numeric($m) ? $m : '"'.$m.'"') . "\n\r";
            }
          }
          else $fileContent .= $i . " = " . (is_numeric($v) ? $v : '"'.$v.'"') . "\n\r";
        }
      }
    }

    if($write_to_file && strlen($fileContent)) return file_put_contents($file, $fileContent, LOCK_EX);
    else return $fileContent;
  }

  public function CreateConfiguration($body) {
    $users = "CREATE TABLE `users` (`id` INT(10) NOT NULL AUTO_INCREMENT,`username` VARCHAR(50) NOT NULL,`password` VARCHAR(256) NOT NULL,`role` INT(3) NOT NULL,`join_timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`)) COLLATE='utf8_general_ci' ENGINE=InnoDB;";

    $admin = "INSERT INTO `users` (`username`, `password`, `role`) VALUES (?, ?, 1);";

    $pages = "CREATE TABLE `pages`( `id` INT(10) NOT NULL AUTO_INCREMENT, `name` VARCHAR(260) NOT NULL DEFAULT '0', `page-title` VARCHAR(32) NOT NULL DEFAULT '0', `author` VARCHAR(50) NOT NULL DEFAULT '0', `content` TEXT NOT NULL DEFAULT '0', `template` TEXT(64) NOT NULL DEFAULT 'default.html', `last-modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY (`id`)) COLLATE='utf8_general_ci' ENGINE=InnoDB;";

    $files = "CREATE TABLE `files`( `id` INT(10) NOT NULL, `filename` VARCHAR(64) NOT NULL, `location` VARCHAR(260) NOT NULL, `upload-timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`id`)) COLLATE='utf8_general_ci' ENGINE=InnoDB;";

    $index = "INSERT INTO `nozomi`.`pages` (`name`, `page-title`, `author`, `content`) VALUES ('index', 'Home', 'nozomi', '<h1>Welcome to Nozomi!</h1>');";

    $configLocation = __DIR__ . '/../../site/config.ini';

    try {
      $s = filter_var($body['sqlh']);
      $d = filter_var($body['sqld']);
      $u = filter_var($body['sqlu']);
      $p = filter_var($body['sqlp']);
      $uu = filter_var($body['user']);
      $options = [
        'cost' => 11,
        'salt' => random_bytes(22)
      ];

      $pass = filter_var($body['pass']);

      $pp = password_hash($pass, PASSWORD_BCRYPT, $options);

      $config = Array (
        'sqlhost' => $s,
        'sqldb' => $d,
        'sqluser' => $u,
        'sqlpass' => $p
      );

      $conn = new PDO("mysql:host=$s;dbname=$d", $u, $p);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $conn->exec($users);
      $conn->prepare($admin)->execute([$uu,$pp]);
      $conn->exec($pages);
      $conn->exec($files);
      $conn->exec($index);

      $this->put_ini_file($config, $configLocation);
      return true;
    }
    catch(Exception $e)
    {
      echo "Setup Fail " . $e->getMessage();
      return false;
    }
  }
}
?>