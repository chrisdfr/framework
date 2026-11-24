<?php

/**
 * Entity.php
 * @Auteur: Christophe Dufour
 * 
 * Classe de base pour toutes les entités.
 */

declare(strict_types=1);

namespace ChrisDfr\Framework\Entities;

use ChrisDfr\Framework\Entities\Traits\Hydrator;

use Deprecated;

abstract class Entity
{
	// Utilisation du trait Hydrator
	use Hydrator;
	
	// Identifiant unique de l'entité
	public int $id {
		set {
			if($value > 0) {
				$this->id = $value;
			}
		}
	}
	// Tableau des éventuelles erreurs
	public protected(set) array $errors = [];
	
	/**
	 * Constructeur
	 * 
	 * @param array $data
	 * [Optionnel]
	 * Un tableau associatif avec les nom des propriétés et leurs valeurs
	 */
	public function __construct(array $data = [])
	{
		if(!empty($data)) {
			$this->hydrate($data);
		}
	}
	
	/**
	 * Retourne la valeur de id
	 * 
	 * @return int
	 */
	#[Deprecated("Accédez directement à l'attribut public \$id")]
	public function id(): int
	{
		return $this->id;
	}
	
	/**
	 * Retourne la valeur de errors
	 * 
	 * @return array
	 */
	#[Deprecated("Accédez directement à l'attribut public \$errors")]
	public function errors(): array
	{
		return $this->errors;
	}
	
	/**
	 * Modifie la valeur de id
	 * 
	 * @param int $value
	 * 
	 * @return void
	 */
	#[Deprecated("Accédez directement à l'attribut public \$id")]
	public function setId(int $id): void
	{
		if($id > 0) {
			$this->id = $id;
		}
	}
	
	/**
	 * Vérifie s'il y a des erreurs dans le tableau $errors
	 * 
	 * @return bool
	 * Retourne true si il y a des erreurs, sinon false
	 */
	public function hasErrors(): bool
	{
		return !empty($this->errors);
	}
	
	/**
	 * Vérifie si c'est un nouvel objet
	 * 
	 * @return bool
	 * Retourne true si c'est un nouvel objet, sinon false
	 */
	public function isNew(): bool
	{
		return empty($this->id);
	}
	
	/**
	 * Vérifie si l'objet est valide
	 * 
	 * @return bool
	 * Retourne true si l'objet est valide, sinon false
	 */
	abstract public function isValid(): bool;
	
}
