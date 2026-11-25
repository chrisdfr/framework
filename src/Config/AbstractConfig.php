<?php

/**
 * AbstractConfig.php
 * @Auteur : Christophe Dufour
 * 
 * Cette classe représentant un tableau avec des paramètres de configuration
 */

declare(strict_types=1);

namespace ChrisDfr\Framework\Config;

use ChrisDfr\Framework\Application;
use ChrisDfr\Framework\ApplicationComponent;
use ChrisDfr\Framework\Exceptions\InvalidJSON;
use SplFileObject;

use Deprecated;

abstract class AbstractConfig extends ApplicationComponent
{
    // Tableau des fichiers de configuration
    protected static array $file = [];

    // Tableau des paramètres de configuration
    // public protected(set) static array $vars = [];

    /**
     * Initialisation
     * 
     * @return void
     */
    #[Deprecated("Méthode vouée à disparaître")]
    abstract static function init(Application $app): void;

    /**
     * Retourne la valeur de vars
     * 
     * @return array|null
     */
    #[Deprecated("Méthode vouée à disparaître")]
    public static function vars(): array|null
    {
        return !empty(self::$vars[static::class]) ? self::$vars[static::class] : null;
    }

    /**
     * Modifie la valeur de vars
     * 
     * @return void
     */
    #[Deprecated("Méthode vouée à disparaître")]
    public function setVars(array $vars): void
    {
        self::$vars[static::class] = $vars;
    }

    /**
     * Lit un fichier de configuration au format json
	 * 
     * @param string $filename
     * 
     * @throws InvalidJSON
     * 
	 * @return bool
     * Retourne true en cas de succès, sinon false
     */
	public static function load(string $filename): bool
	{
        if(!is_readable($filename)) {
            return false;
        }

        $file = new SplFileObject($filename);
        $content = $file->fread($file->getSize());

        if(is_null($json = json_decode($content, true))) {
            throw new InvalidJSON("Erreur de decodage JSON: " . self::jsonErrorMsg());
        }

        //self::$vars[static::class] = json_decode($content, true);
        static::$vars = $json;
        return true;
	}

    /**
     * Retourne une chaine de caractère représentant la dernière erreur survenue par le decodage json
     * 
     * @return string
     * Retourne une chaine de caractère
     */
    protected static function jsonErrorMsg(): string
    {
        switch(json_last_error()) {
            case JSON_ERROR_DEPTH:
                $msg = "Profondeur maximale atteinte";
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $msg = "JSON invalide ou mal formé";
                break;
            case JSON_ERROR_CTRL_CHAR:
                $msg = "Erreur de caractère de contrôle";
                break;
            case JSON_ERROR_SYNTAX:
                $msg = "Erreur de syntaxe";
                break;
            case JSON_ERROR_UTF8:
                $msg = "Caractères UTF-8 malformés";
                break;
            default:
                $msg = "Erreur inconnue";
                break;
        }

        return $msg;
    }

    /**
	 * Ecrit un fichier de paramètres au format json
	 * 
	 * @return int|false
     * Retourne le nombre d'octets écrits, ou false si une erreur survient.
     * @throws RuntimeException
	 */
	public static function write(): int|false
	{
        if(empty(self::$vars[static::class])) {
            throw new \RuntimeException("Impossible d'écrire un tableau de paramètres vide!");
        }

		$file = new \SplFileObject(self::$file[static::class], "w");
		//return $file->fwrite(json_encode(self::$vars[static::class], JSON_PRETTY_PRINT));
        return $file->fwrite(json_encode(static::$vars, JSON_PRETTY_PRINT));
	}
    
}
