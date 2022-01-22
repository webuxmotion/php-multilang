<?php

class Router {
  protected static $routes = [];
  protected static $route = [];

  public static function add($regexp, $route = []) {
    self::$routes[$regexp] = $route;
  }

  public static function getRoutes() {
    return self::$routes;
  }

  public static function getRoute() {
    return self::$route;
  }

  public static function dispatch($url) {
    if (self::matchRoute($url)) {
      
      $controller = self::$route['controller'];
      $controller = self::upperCamelCase($controller);
      
      if (class_exists($controller)) {
        $controllerInstance = new $controller;

        $action = self::$route['action'] . 'Action';
        $action = self::lowerCamelCase($action);

        if (method_exists($controllerInstance, $action)) {
          $controllerInstance->$action();
        } else {
          echo "Method <b>$action</b> not found";
        }
      } else {
        echo "Controller <b>$controller</b> not found";
      }
    } else {
      http_response_code(404);
      
      include '404.html';
    }
  }

  public static function matchRoute($url) {
    foreach (self::$routes as $pattern => $route) {
      $isMatched = preg_match("#$pattern#i", $url, $matches);
      
      if ($isMatched) {
        foreach ($matches as $key => $value) {
          if (is_string($key)) {
            $route[$key] = $value;
          }
        }

        if (!isset($route['action'])) {
          $route['action'] = 'index';
        }

        self::$route = $route;

        return true;
      }
    }

    return false;
  }

  protected static function upperCamelCase($name) {
    $name = str_replace('-', ' ', $name);
    $name = ucwords($name);
    $name = str_replace(' ', '', $name);
    
    return $name;
  }

  protected static function lowerCamelCase($name) {
    $name = self::upperCamelCase($name);
    $name = lcfirst($name);

    return $name;
  }
}