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
}
