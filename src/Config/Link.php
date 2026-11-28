<?php

/**
 * Link.php
 * @Auteur : Christophe Dufour
 * 
 * Gère les liens de l'application
 */

declare(strict_types=1);

namespace ChrisDfr\Framework\Config;

class Link extends AbstractConfig
{
    // Tableau des paramètres
    public protected(set) static array $setting;

    /**
     * Ajoute ou modifie une variable du tableau des paramètres
     * 
     * @param string $key
     * 
     * @param mixed $value
     * 
     * @return void
     */
    public static function set(string $key, mixed $value): void
    {
        self::$setting[$key] = $value;
    }
}
