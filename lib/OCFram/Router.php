<?php
namespace OCFram;

class Router
{
  static public $routes = array( 'Backend' => array(), 'Frontend' => array() );

  const NO_ROUTE = 1;

  public function addRoute(Route $route, $app)
  {
    if (!in_array($route, self::$routes[$app]))
    {
      self::$routes[$app][] = $route;
    }
  }

  public function buildRouteForApplication($app)
  {
    $xml = new \DOMDocument;
    $xml->load(__DIR__.'/../../App/'.$app.'/Config/routes.xml');

    $routes = $xml->getElementsByTagName('route');

    // On parcourt les routes du fichier XML.
    foreach ($routes as $route)
    {
      $vars = [];

      // On regarde si des variables sont présentes dans l'URL.
      if ($route->hasAttribute('vars'))
      {
        $vars = explode(',', $route->getAttribute('vars'));
      }

      // On ajoute la route au routeur.
      $this->addRoute(new Route($route->getAttribute('url'), $route->getAttribute('module'), $route->getAttribute('action'), $vars, $route->getAttribute('pattern')), $app);
    }
  }

  public function getRoute($url, $app)
  {
    foreach (self::$routes[$app] as $route)
    {
      // Si la route correspond à l'URL
      if (($varsValues = $route->match($url)) !== false)
      {
        // Si elle a des variables
        if ($route->hasVars())
        {
          $varsNames = $route->varsNames();
          $listVars = [];

          // On crée un nouveau tableau clé/valeur
          // (clé = nom de la variable, valeur = sa valeur)
          foreach ($varsValues as $key => $match)
          {
            // La première valeur contient entièrement la chaine capturée (voir la doc sur preg_match)
            if ($key !== 0)
            {
              $listVars[$varsNames[$key - 1]] = $match;
            }
          }

          // On assigne ce tableau de variables � la route
          $route->setVars($listVars);
        }

        return $route;
      }
    }

    throw new \RuntimeException('Aucune route ne correspond à l\'URL', self::NO_ROUTE);
  }

  public function getUrl($app, $module, $action, array $inputs)
  {
   foreach (self::$routes[$app] as $route)
    {
      if ($route->module() == $module && $route->action() == $action)
      {
        $url = $route->pattern();
        $vars = explode(',', implode(',',$route->varsNames()));

        // On vérifie que le nombre de variables passées en paramètres correspond au nombre de variables du pattern
        if (count($vars) != count($inputs))
        {
          throw new \Exception('Nombre de variables incorrect');
        }

        // On vérifie que le nom des variables passées en paramètres correspond aux placeholders de du pattern
        foreach($inputs as $key => $value)
        {
          if (!in_array($key, $vars))
          {
            throw new \Exception('La variable : '.$key.' n\'est pas dans l url!');
          }

          $position_start = strpos($url, '%'.$key.'%');
          $length = strlen($key)+2;
          $url = substr_replace($url, $value, $position_start, $length);
        }

        return $url;
      }
    }
  }
}