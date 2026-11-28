<?php

/**
 * Route.php
 * @Auteur: Christophe Dufour
 * 
 * Classe modélisant une route
 */

declare(strict_types=1);

namespace ChrisDfr\Framework\Router;

class Route
{
	// Url de la route
	public protected(set) string $url;
	// Contrôlleur qu'il faudra instancier
	public protected(set) string $controller;
	// La méthode qu'il faudra appeler
	public protected(set) string $method;
	// Variable à initialiser à partir de l'url
	public protected(set) array $varsNames;
	// Tableau des variables
	public array $vars = [];

	/**
	 * Constructeur
	 * 
	 * @param string $url
	 * 
	 * @param string $controller
	 * 
	 * @param string $method
	 * 
	 * @param array $varsNames
	 */
	public function __construct(string $url, string $controller, string $method, array $varsNames)
	{
		$this->url = $url;
		$this->controller = $controller;
		$this->method = $method;
		$this->varsNames = $varsNames;
	}
	
	/**
	 * Vérifie si le tableau varsNames n'est pas vide
	 * 
	 * @return bool
	 */
	public function hasVars(): bool
	{
		return !empty($this->varsNames);
	}
	
	/**
	 * Retourne une route qui corespont à un masque corespond
	 * 
	 * @param string $url
	 * 
	 * @return array|false
	 */
	public function match(string $url): array|false
	{
		if(preg_match("#^" . $this->url . "$#", $url, $matches)) {
			return $matches;
		}
		
		return false;
	}

}
