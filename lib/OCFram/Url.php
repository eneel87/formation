<?php
namespace OCFram;

class Url
{
    public function url_construct($app, $module, $action, array $inputs)
    {
        $xml = new \DOMDocument;
        $xml->load(__DIR__ . '/../../App/' .$app. '/Config/routes.xml');

        $routes = $xml->getElementsByTagName('route');

        foreach ($routes as $route)
        {
            if ($route->getAttribute('module') == $module && $route->getAttribute('action') == $action)
            {
                $url = $route->getAttribute('pattern');
                $vars = explode(',', $route->getAttribute('vars'));

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