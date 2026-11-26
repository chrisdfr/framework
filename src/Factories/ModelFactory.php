<?php

/**
 * ModelFactory.php
 * @Auteur: Christophe Dufour
 * 
 * Crée le manageur demandé.
 */

declare(strict_types=1);

namespace ChrisDfr\Framework\Factories;

use ChrisDfr\Framework\Exceptions\FactoryError;
use ChrisDfr\Framework\Exceptions\NoDatabaseConnection;
use ChrisDfr\Framework\Factories\Enums\DatabaseAPI;
use ValueError;

class ModelFactory
{
	// L'api utilisé pour accéder à la base de données
	protected DatabaseAPI $api;
	// Le lien avec la base de données
	protected mixed $dao;
	// Tableau des modeles
	protected array $factories = [];
	
	/**
	 * Constructeur
	 * 
	 * @param DatabaseAPI $api
	 * 
	 * @param mixed $dao
	 */
	public function __construct(DatabaseAPI $api, mixed $dao)
	{
		$this->api = $api;
		$this->dao = $dao;
	}
	
	/**
	 * Retourne le modèle demandé
	 * 
	 * @param string $model
	 * 
	 * @return mixed
	 * 
	 * @throws NoDatabaseConnection
	 * 
	 * @throws FactoryError
	 * 
	 * @throws ValueError
	 */
	public function create(string $model): mixed
	{
		if(is_null($this->dao)) {
			throw new NoDatabaseConnection("Aucune connexion avec une base de données.");
		}

		if(empty($model)) {
			throw new ValueError('Le modèle ne peut pas être une chaine de caractère vide.');
		}
		
		if(!isset($this->factories[$model])) {
			$factory = "Lib\Models\\" . $model . "Model" .  $this->api->value;
		
			if(!class_exists($factory)) {
				throw new FactoryError("La classe usine $factory n'a pas peu être trouvée.");
			}
			
			$this->factories[$model] = new $factory($this->dao);
		}
		
		return $this->factories[$model];
	}

}
