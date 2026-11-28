<?php

/**
 * Application.php
 * @Auteur: Christophe Dufour
 * 
 * Classe modélisant une application avec ses requêtes et ses réponces
 */

declare(strict_types=1);

namespace ChrisDfr\Framework;

use ChrisDfr\Framework\Exceptions\InvalidRoute;
use ChrisDfr\Framework\Handlers\Error;
use ChrisDfr\Framework\Handlers\ErrorsHandler;
use ChrisDfr\Framework\Router\Router;
use ChrisDfr\Framework\User\User;

abstract class Application
{
	// Dossier racine de l'application
	public protected(set) string $rootDir;
	// Dossier des fichiers sources de l'application
	public protected(set) string $appDir;
	// Objet représentant une requête html
	public protected(set) HTTPRequest $httpRequest;
	// Objet représentant une réponce html
	public protected(set) HTTPResponse $httpResponse;
	// Objet représentant l'utilisateur
	public protected(set) User $user;
	// Objet pour l'interception des exceptions
	public protected(set) Error $errorsHandler;
	// Factory de la base de données
	public protected(set) mixed $dbFactory = null;
	
	/**
	 * Constructeur
	 */
	public function __construct(string $rootDir, string $appDir)
	{
		$this->rootDir = $rootDir;
		$this->appDir = $appDir;
		$this->httpRequest = new HTTPRequest($this);
		$this->httpResponse = new HTTPResponse($this);
		$this->user = new User($this);
		$this->errorsHandler = new ErrorsHandler($this);
	}

	/**
	 * Retourne le controleur à éxécuter
	 * 
	 * @return BackController
	 */
	public function getController(string $ns): BackController
	{
		require_once $this->appDir . str_replace("/", DIRECTORY_SEPARATOR, "/routes/routes.php");

		try {
			$matchedRoute = Router::getRoute($this->httpRequest->requestURI());
		}
		catch (InvalidRoute $e) {
			$this->httpResponse->redirect404();
		}
		
		$_GET = array_merge($_GET, $matchedRoute->vars);
		$controllerClass = $ns . "\\Controllers\\" . $matchedRoute->controller . "\\" . $matchedRoute->controller . "Controller";
		
		return new $controllerClass($this, $matchedRoute->controller, $matchedRoute->method);
	}

	/**
	 * Lance l'application
	 * 
	 * @return void
	 */
	abstract public function run(): void;	
}
