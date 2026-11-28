<?php

/**
 * Config.php
 * @Auteur : Christophe Dufour
 * 
 * Gère les paramètres de configuration
 */

declare(strict_types=1);

namespace ChrisDfr\Framework\Config;

class Config extends AbstractConfig
{
    // Tableau des paramètres
    public protected(set) static array $setting;

    /**
	 * Modifie la valeur d'une variable dans le tableau des configurations si celle-ci existe.
	 * 
	 * @param string $key
     * 
     * @param mixed $value
     * 
	 * @return void
	 */
	public static function set(string $key, mixed $value, string $index): void
	{
        self::$setting[$index][$key] = $value;
	}

    /**
     * Crée la structure du tableau des paramètres
     * 
     * @return void
     */
    public static function create(): void
    {
        self::$setting = array(
            'database' => array(
                'dbname' => "",
                'username' => "",
                'password' => ""
            ),
            'global' => array(),
            'backend' => array(),
            'frontend' => array()
        );
    }
}
