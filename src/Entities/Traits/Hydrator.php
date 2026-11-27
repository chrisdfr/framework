<?php

/**
 * Hydratator.php
 * @Auteur: Christophe Dufour
 * 
 * Permet d'initialiser les différents attributs d'un objet
 */

namespace ChrisDfr\Framework\Entities\Traits;

use PropertyHookType;
use ReflectionClass;

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
		$rClass = new ReflectionClass(static::class);
		
		foreach($data as $key => $value) {
			$rProp = $rClass->getProperty($key);

			if($rProp->hasHook(PropertyHookType::Set)) {
				$this->$key = $value;
			}
			else {
				$method = "set" . ucfirst($key);
			
				if(is_callable(array($this, $method))) {
					$this->$method($value);
				}
			}
		}
	}
	
}
