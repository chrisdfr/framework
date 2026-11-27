<?php

/**
 * EntityFactory.php
 * @Auteur: Christophe Dufour
 * 
 * Créateur pour les entités
 */

declare(strict_types=1);

namespace ChrisDfr\Framework\Factories;

use ChrisDfr\Framework\Entities\Entity;
use ChrisDfr\Framework\Exceptions\EntityError;
use ValueError;

class EntityFactory
{
    /**
     * 
     * @param string $entity
     * 
     * @param array $datas
     * 
     * @throws EntityError
     * 
     * @return Entity
     */
    public function create(string $entity, array $datas = []): Entity
    {
        if(empty($entity)) {
			throw new ValueError('L\'entité ne peut pas être une chaine de caractère vide.');
		}

        $entity = "Lib\Entities\\" . $entity;

        if(!class_exists($entity)) {
            throw new EntityError("Entité inexistante:  $entity");
        }
        
        return new $entity($datas);
    }

}
