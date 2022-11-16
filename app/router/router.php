<?php

function routes()  // pega as rotas do routes.php
{

  return require 'routes.php';
}

function exactMatchUriInArrayRoutes($uri, $routes) {
    if(array_key_exists($uri, $routes)) {
      return [$uri => $routes[$uri]];
    }

    return [];
}

function regularExpressionMatchArrayRouter($routes, $uri) {

  return array_filter(
    $routes,
      function($value) use($uri){
        $regex = str_replace('/', '\/', ltrim($value, '/'));
        return preg_match("/^$regex$/", ltrim($uri, '/'));
      },
      ARRAY_FILTER_USE_KEY
  );
}

function params($uri, $matchedUri) {

  if(!empty($matchedUri)) {
      $matchedToGetParams = array_keys($matchedUri)[0];
      return array_diff(
        explode('/', ltrim($uri, '/')),
        explode('/', ltrim($matchedToGetParams, '/')),
      );
  }
  return [];

}

function router() 
{
  $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); //Pega a url que o usuario procurou
  $routes = routes(); // pega as rotas do routes.php

  $matchedUri = exactMatchUriInArrayRoutes($uri, $routes); 

  if(empty($matchedUri)) {
    $matchedUri = regularExpressionMatchArrayRouter($uri, $routes);
    if(!empty($matchedUri)) {
      $params = params($uri, $matchedUri);
      $uri = explode('/', ltrim($uri));
      $paramsData = [];
      foreach ($params as $index => $param) {
        $paramsData[$uri[$index - 1]] = $param;
        $uri[0];
      }
      var_dump($paramsData);
      die();
    }
  }
  var_dump($matchedUri);
  die();
  
}