<?php

/**
 * ApplicationComponent.php
 * @Auteur: Christophe Dufour
 * 
 * Classe de base pour les composantes de l'application
 */

namespace ChrisDfr\Framework;

use Deprecated;

abstract class ApplicationComponent
{
	// Instance de l'application
	public protected(set) Application $app {
		get => $this->app;
	}
	
	/**
	 * Constructeur
	 * 
	 * @param Application $app
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}
	
	/**
	 * Retourne la valeur de $app
	 * 
	 * @return Application 
	 */
	#[Deprecated("Accédez directement à l'attribut \$app qui est public en lecture")]
	public function app(): Application
	{
		return $this->app;
	}
	
}
