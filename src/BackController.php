<?php

/**
 * BackController.php
 * @Auteur: Christophe Dufour
 * 
 * Controleur de base pour tous les controleurs d'une application
 */

declare(strict_types=1);

namespace ChrisDfr\Framework;

use ChrisDfr\Framework\Config\Config;
use ChrisDfr\Framework\Factories\EntityFactory;
use ChrisDfr\Framework\Factories\Enums\DatabaseAPI;
use ChrisDfr\Framework\Factories\ModelFactory;
use ChrisDfr\Framework\View\View;
use Deprecated;
use RuntimeException;
use ValueError;

abstract class BackController extends ApplicationComponent
{
	// Créateur pour les modèles
	protected ModelFactory $modelFactory;
	// Créateur pour les entités
	protected EntityFactory $entityFactory;

	// Le nom du contrôleur actuel
	public string $controller = "" {
		set {
			if(empty($value)) {
				throw new ValueError("Le controller ne peut pas être une chaine de caractère vide");
			}

			$this->controller = $value;
		}
	}

	// La méthode à éxécuter
	public string $method = "" {
		set {
			if(empty($value)) {
				throw new ValueError("La méthode ne peut pas être une chaine de caractère vide");
			}
		
			$this->method = $value;
		}
	}

	// Le nom du fichier vue à afficher
	public string $viewFile = "" {
		set {
			if(empty($value)) {
				throw new ValueError("La vue ne peut pas être une chaine de caractère vide");
			}
		
			$this->viewFile = $value;
			$this->view->viewFile = $this->viewFile;
		}
	}
	
	// La vue à afficher
	public protected(set) View $view;
	
	/**
	 * Constructeur
	 * 
	 * @param Application $app
	 * 
	 * @param string $controller
	 * 
	 * @param string $method
	 */
	public function __construct(Application $app, string $controller, string $method)
	{
		parent::__construct($app);
		//$this->modelFactory = new ModelFactory(DatabaseAPI::from(Config::get(AreaConfig::Database, 'api')), $this->app->dbFactory->get());
		$this->modelFactory = new ModelFactory(DatabaseAPI::from(Config::$vars['database']['api']), $this->app->dbFactory->get());
		$this->entityFactory = new EntityFactory;
		$this->view = new View($app, $controller);
		$this->controller = $controller;
		$this->method = $method;
		$this->viewFile = $method;
	}
	
	/**
	 * Retourne la valeur de view
	 * 
	 * @return View
	 */
	#[Deprecated("Accédez directement à l'attribut \$view qui est public en lecture")]
	public function view(): View
	{
		return $this->view;
	}
	
	/**
	 * Modifie la valeur de controller
	 * 
	 * @param string $controller
	 * 
	 * @return void
	 * 
	 * @throws ValueError
	 */
	#[Deprecated("Accédez directement à l'attribut protected \$controller ")]
	public function setController(string $controller): void
	{
		if(empty($controller)) {
			throw new ValueError("Le controller ne peut pas être une chaine de caractère vide");
		}
		
		$this->controller = $controller;
	}
	
	/**
	 * Modifie la valeur de method
	 * 
	 * @param string $method
	 * 
	 * @return void
	 * 
	 * @throws ValueError
	 */
	#[Deprecated("Accédez directement à l'attribut protected \$method ")]
	public function setMethod(string $method): void
	{
		if(empty($method)) {
			throw new ValueError("La méthode ne peut pas être une chaine de caractère vide");
		}
		
		$this->method = $method;
	}
	
	/**
	 * Modifie la valeur de viewFile
	 * 
	 * @param string $viewFile
	 * 
	 * @return void
	 * 
	 * @throws ValueError
	 */
	#[Deprecated("Accédez directement à l'attribut protected \$viewFile ")]
	public function setViewFile(string $viewFile): void
	{
		if(empty($viewFile)) {
			throw new ValueError("La vue ne peut pas être une chaine de caractère vide");
		}
		
		$this->viewFile = $viewFile;
		$this->view->setViewFile($this->viewFile);
	}
	
	/**
	 * Exécute la méthode demandée si celle-ci existe
	 * 
	 * @return void
	 * 
	 * @throws RuntimeException
	 */
	public function execute(): void
	{
		$method = "execute" . ucfirst($this->method);
		
		if(!is_callable(array($this, $method))) {
			throw new RuntimeException("La méthode $this->method n'est pas définie sur ce controller");
		}
		
		$this->$method($this->app->httpRequest);
	}
	
}
