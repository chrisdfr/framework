<?php

/**
 * Route.php
 * @Auteur: Christophe Dufour
 * 
 * Classe modélisant une route
 */

declare(strict_types=1);

namespace ChrisDfr\Framework\Router;

use Deprecated;

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
	 * Retourne la valeur de url
	 * 
	 * @return string
	 */
	#[Deprecated("Accédez directement à l'attribut public \$url")]
	public function url(): string
	{
		return $this->url;
	}
	
	/**
	 * Retourne la valeur de controller
	 * 
	 * @return string
	 */
	#[Deprecated("Accédez directement à l'attribut public \$controller")]
	public function controller(): string
	{
		return $this->controller;
	}
	
	/**
	 * Retourne la valeur de method
	 * 
	 * @return string
	 */
	#[Deprecated("Accédez directement à l'attribut public \$method")]
	public function method(): string
	{
		return $this->method;
	}
	
	/**
	 * Retourne la valeur de varsNames
	 * 
	 * @return string
	 */
	#[Deprecated("Accédez directement à l'attribut public \$varsNames")]
	public function varsNames(): array
	{
		return $this->varsNames;
	}
	
	/**
	 * Retourne la valeur de vars
	 * 
	 * @return string
	 */
	#[Deprecated("Accédez directement à l'attribut public \$vars")]
	public function vars(): array
	{
		return $this->vars;
	}
	
	/**
	 * Modifie la valeur de url
	 * 
	 * @param string $url
	 * 
	 * @return void
	 */
	#[Deprecated("Accédez directement à l'attribut public \$url")]
	public function setUrl(string $url): void
	{
		if(is_string($url)) {
			$this->url = $url;
		}
	}
	
	/**
	 * Modifie la valeur de controller
	 * 
	 * @param string $controller
	 * 
	 * @return void
	 */
	#[Deprecated("Accédez directement à l'attribut public \$controller")]
	public function setController(string $controller): void
	{
		if(is_string($controller)) {
			$this->controller = $controller;
		}
	}
	
	/**
	 * Modifie la valeur de method
	 * 
	 * @param string $method
	 * 
	 * @return void
	 */
	#[Deprecated("Accédez directement à l'attribut public \$method")]
	public function setMethod(string $method): void
	{
		if(is_string($method)) {
			$this->method = $method;
		}
	}
	
	/**
	 * Modifie la valeur de varsNames
	 * 
	 * @param array $varsNames
	 * 
	 * @return void
	 */
	#[Deprecated("Accédez directement à l'attribut public \$varsNames")]
	public function setVarsNames(array $varsNames): void
	{
		$this->varsNames = $varsNames;
	}
	
	/**
	 * Modifie la valeur de vars
	 * 
	 * @param array $vars
	 * 
	 * @return void
	 */
	#[Deprecated("Accédez directement à l'attribut public \$vars")]
	public function setVars(array $vars): void
	{
		$this->vars = $vars;
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
