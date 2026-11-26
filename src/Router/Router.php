<?php

/**
 * Router.php
 * @Auteur: Christophe Dufour
 * 
 * Gére les différentes route de l'application
 */

declare(strict_types=1);

namespace ChrisDfr\Framework\Router;

use ChrisDfr\Framework\Exceptions\InvalidRoute;

class Router
{
    // Tableau des routes
    private static array $routes = [];

    /**
     * Recherche et retourne une route en fonction d'une url
     * 
     * @param string $url
     * 
     * @return Route
     * 
     * @throws InvalidRoute
     */
    public static function getRoute(string $url): Route
    {
        foreach(self::$routes as $route) {
			if(($varsValues = $route->match($url)) !== false) {
				if($route->hasVars()) {
					$varsNames = $route->varsNames;
					$listVars = array();
					
					foreach($varsValues as $key => $match) {
						if($key !== 0) {
							$listVars[$varsNames[$key - 1]] = $match;
						}
					}
					
					$route->vars = $listVars;
				}
				
				return $route;
			}
		}
		
		throw new InvalidRoute("Aucune route ne correspond cette url. ($url)");
    }

    /**
     * Ajoute une route
     * 
     * @param string $url
     * 
     * @param string $controller
     * 
     * @param string $method
     * 
     * @param string $vars
     * [Optionnel]
     * Variable à initialiser à partir de l'url
     * @return void
     */
    public static function set(string $url, string $controller, string $method, string $varsNames = ""): void
    {
        $vars = [];

        if(!empty($varsNames)) {
            $vars = explode(',', $varsNames);
        }

        self::$routes[] = new Route($url, $controller, $method, $vars);
    }

}
