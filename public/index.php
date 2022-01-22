<?php

define('WWW', __DIR__);
define('ROOT', dirname(__DIR__));
define('CORE', ROOT . '/vendor/core');
define('APP', ROOT . '/app');

$query = rtrim($_SERVER['QUERY_STRING'], '/');

require '../vendor/core/Router.php';
require '../vendor/libs/functions.php';

spl_autoload_register(function($class) {
  $file = APP . "/controllers/$class.php";

  if (is_file($file)) {
    require_once($file);
  }
});

Router::add('^pages/?(?P<action>[a-z-]+)?$', ['controller' => 'Posts']);

// defaults routes
Router::add('^$', ['controller' => 'Main', 'action' => 'index']);
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');

Router::dispatch($query);