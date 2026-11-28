<?php

/**
 * AbstractConfig.php
 * @Auteur : Christophe Dufour
 * 
 * Cette classe représentant un tableau avec des paramètres de configuration
 */

declare(strict_types=1);

namespace ChrisDfr\Framework\Config;

use ChrisDfr\Framework\ApplicationComponent;
use ChrisDfr\Framework\Exceptions\InvalidJSON;
use RuntimeException;
use SplFileObject;

abstract class AbstractConfig extends ApplicationComponent
{
    /**
     * Lit un fichier de configuration au format json
	 * 
     * @param string $filename
     * 
     * @throws RuntimeException
     * 
     * @throws InvalidJSON
     * 
	 * @return void
     */
	public static function load(string $filename): void
	{
        if(!is_readable($filename)) {
             throw new RuntimeException("Fichier de configuration introuvable: " . basename($filename));
        }

        $file = new SplFileObject($filename);
        $content = $file->fread($file->getSize());

        if(is_null($json = json_decode($content, true))) {
            throw new InvalidJSON("Erreur de decodage JSON: " . self::jsonErrorMsg());
        }

        static::$setting = $json;
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
}
