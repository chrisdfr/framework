<?php

/**
 * Hydratator.php
 * @Auteur: Christophe Dufour
 * 
 * Permet d'initialiser les différents attributs d'un objet
 */

declare(strict_types=1);

namespace ChrisDfr\Framework\Entities\Traits;

trait Hydrator
{	
	/**
	 * Initialise un objet
	 * 
	 * @param array $data
	 * 
	 * @return void
	 */
	public function hydrate(array $data): void
	{
		foreach($data as $key => $value) {
			$method = "set" . ucfirst($key);
			
			if(is_callable(array($this, $method))) {
				$this->$method($value);
			}
		}
	}
	
}
