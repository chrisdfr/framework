<?php

/**
 * ApplicationComponent.php
 * @Auteur: Christophe Dufour
 * 
 * Classe de base pour les composantes de l'application
 */

declare(strict_types=1);

namespace ChrisDfr\Framework;

abstract class ApplicationComponent
{
	// Instance de l'application
	public protected(set) Application $app;
	
	/**
	 * Constructeur
	 * 
	 * @param Application $app
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}
}
