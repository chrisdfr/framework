<?php

/**
 * Application.php
 * @Auteur: Christophe Dufour
 * 
 * Classe modélisant une application avec ses requêtes et ses réponces
 */

declare(strict_types=1);

namespace ChrisDfr\Framework;

use ChrisDfr\Framework\Config\Config;
use ChrisDfr\Framework\Config\Link;
use ChrisDfr\Framework\Config\Enums\AreaConfig;
use ChrisDfr\Framework\Exceptions\InvalidRouteException;
use ChrisDfr\Framework\Factories\DBFactory;
use ChrisDfr\Framework\Factories\Enums\DatabaseAPI;
use ChrisDfr\Framework\Handlers\ErrorHandler;
use ChrisDfr\Framework\Router\Router;
use ChrisDfr\Framework\User\User;
use Deprecated;

abstract class Application
{
	// Dossier racine de l'application
	public protected(set) string $rootDir;
	// Dossier des fichiers sources de l'application
	public protected(set) string $appDir;
	// Namespace de l'application où l'on se trouve
	public protected(set) string $nameSpace;
	// Objet représentant une requête html
	public protected(set) HTTPRequest $httpRequest;
	// Objet représentant une réponce html
	public protected(set) HTTPResponse $httpResponse;
	// Objet représentant l'utilisateur
	public protected(set) User $user;
	// Factory de la base de données
	public protected(set) mixed $dbFactory;
	
	/**
	 * Constructeur
	 */
	public function __construct()
	{
		$classname = get_called_class();
		$this->rootDir = dirname($_SERVER['DOCUMENT_ROOT']);
		$this->nameSpace = substr($classname, 0, strrpos($classname, '\\'));
		$this->appDir = $this->rootDir . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, "/src/" . $this->nameSpace);
		$this->httpRequest = new HTTPRequest($this);
		$this->httpResponse = new HTTPResponse($this);
		$this->user = new User($this);

		Config::init($this);
		Link::init($this);
		$this->dbFactory = new DBFactory(DatabaseAPI::from(Config::get(AreaConfig::Database, 'api')),
															Config::get(AreaConfig::Database, 'dbname'), 
															Config::get(AreaConfig::Database, 'username'),
															Config::get(AreaConfig::Database, 'password'));
		ErrorHandler::init($this);
	}
	
	/**
	 * Retourne la valeur de rootDir
	 * 
	 * @return string
	 */
	#[Deprecated("Accédez directement à l'attribut \$rootDir qui est public en lecture")]
	public function rootDir(): string
	{
		return $this->rootDir;
	}

	/**
	 * Retourne la valeur de appDir
	 * 
	 * @return string
	 */
	#[Deprecated("Accédez directement à l'attribut \$appDir qui est public en lecture")]
	public function appDir(): string
	{
		return $this->appDir;
	}

	/**
	 * Retourne la valeur de nameSpace
	 * 
	 * @return string
	 */
	#[Deprecated("Accédez directement à l'attribut \$nameSpace qui est public en lecture")]
	public function nameSpace(): string
	{
		return $this->nameSpace;
	}

	/**
	 * Retourne la valeur de httpRequest
	 * 
	 * @return HTTPRequest
	 */
	#[Deprecated("Accédez directement à l'attribut \$httpRequest qui est public en lecture")]
	public function httpRequest(): HTTPRequest
	{
		return $this->httpRequest;
	}
	
	/**
	 * Retourne la valeur de httpResponse
	 * 
	 * @return HTTPResponse
	 */
	#[Deprecated("Accédez directement à l'attribut \$httpResponse qui est public en lecture")]
	public function httpResponse(): HTTPResponse
	{
		return $this->httpResponse;
	}
	
	/**
	 * Retourne la valeur de user
	 * 
	 * @return User
	 */
	#[Deprecated("Accédez directement à l'attribut \$user qui est public en lecture")]
	public function user(): User
	{
		return $this->user;
	}

	/**
	 * Retourne la valeur de dbFactory
	 * 
	 * @return mixed
	 */
	#[Deprecated("Accédez directement à l'attribut \$dbFactory qui est public en lecture")]
	public function dbFactory(): mixed
	{
		return $this->dbFactory;
	}

	/**
	 * Retourne le controleur à éxécuter
	 * 
	 * @return BackController
	 */
	public function getController(): BackController
	{
		require_once $this->appDir . str_replace("/", DIRECTORY_SEPARATOR, "/routes/routes.php");

		try {
			$matchedRoute = Router::getRoute($this->httpRequest->requestURI());
		}
		catch (InvalidRouteException $e) {
			$this->httpResponse->redirect404();
		}
		
		$_GET = array_merge($_GET, $matchedRoute->vars);
		$controllerClass = $this->nameSpace . "\\Controllers\\" . $matchedRoute->controller . "\\" . $matchedRoute->controller . "Controller";
		
		return new $controllerClass($this, $matchedRoute->controller, $matchedRoute->method);
	}

	/**
	 * Lance l'application
	 * 
	 * @return void
	 */
	abstract public function run(): void;
	
}
