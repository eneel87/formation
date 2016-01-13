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

                // On v�rifie que le nombre de variables pass�es en param�tres correspond au nombre de variables du pattern
                if (count($vars) != count($inputs))
                {
                    throw new \Exception('Nombre de variables incorrect');
                }

                // On v�rifie que le nom des variables pass�es en param�tres correspond aux placeholders de du pattern
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