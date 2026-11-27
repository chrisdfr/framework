<?php

/**
 * AbstractErrorHandler.php
 * @Auteur: Christophe Dufour
 * 
 * Classe de base pour les gestionnaires d'erreurs
 */

declare(strict_types=1);

namespace ChrisDfr\Framework\Handlers;

use ChrisDfr\Framework\ApplicationComponent;
use ChrisDfr\Framework\Handlers\Enums\State;
use RuntimeException;
use Throwable;
use ValueError;

abstract class Error extends ApplicationComponent
{
    /**
     * La vue à afficher en cas d'erreur
     */
    public public(set) string $view {
        set {
            if(empty($value)) {
                throw new ValueError("La vue ne peut pas être une chaine de caractère vide");
            }

            $this->view = $value;
        }
    }

    /**
     * Le nom du fichier où il faut enregistrer les log
     */
    public string|null $filename = null {
        set {
            if(empty($value)) {
                throw new ValueError("Le nom du fichier de log ne peut pas être une chaine de caractère vide");
            }
            
            $fp = @fopen($value, "w");
            
            if(!$fp) {
                throw new RuntimeException("Impossible d'accèder au fichier de log (" . basename($value) . "): autorisation refusée");
            }

            fclose($fp);
            
            if(stat($value)['gid'] == 33) {
                chmod($value, 0660);
                chgrp($value, "www");
            }

            $this->filename = $value;
        }
    }

    /**
     * Active, désactive ou retourne l'état de l'affichage des messages d'erreus de PHP
     * 
     * @param State|null $state = null
     * [optionnel]
     * @return string|false
     */
    public function displayErrors(State|null $state = null): string|false
    {
        if(is_null($state)) {
            return ini_get("display_errors");
        }

        return ini_set("display_errors", $state->value);
    }

    /**
     * Retourne ou modifie la valeur de l'option de configuration log_errors de PHP
     * 
     * @param State|null $state = null
     * [optionnel]
     * @return string|false
     */
    public static function logErrors(State|null $state = null): string|false
    {
        if(is_null($state)) {
            return ini_get("log_errors");
        }

        return ini_set("log_errors", $state->value);
    }

    /**
     * Retourne ou modifie la valeur de l'option de configuration error_log de PHP
     * 
     * @param string|null $filename = null
     * [optionnel]
     * @return string|false
     */
    public static function errorLog(string|null $filename = null): string|false
    {
        if(is_null($filename)) {
            return ini_get('error_log');
        }

        if(!is_writable($filename)) {
            return false;
        }

        return ini_set("error_log", $filename);
    }

    /**
	 * Fixe le niveau de rapport d'erreurs PHP
	 * 
	 * @param int $error_level
	 * [Optionnel]
	 * Le niveau de rapport d'erreurs
	 * @return int
	 */
	public function errorReporting(?int $error_level = null): int
	{
		return error_reporting($error_level);
	}

    /**
     * Définit une fonction pour la gestion des erreurs
     * 
     * @param ?callable $callback
     * [optionnel] La fonction à appeler quand une erreur se produit
     * @param int $error_levels = E_ALL
     * [optionnel] Le niveau d'erreur
     * @return ?callable
     */
    /*
    public static function setErrorHandler(?callable $callback, int $error_levels = E_ALL): ?callable
    {
        return set_error_handler($callback, $error_levels);
    }
    */

    /**
     * Définit une fonction pour la gestion d'exceptions 
     * 
     * @return ?callable
     */
    public function setExceptionHandler(): ?callable
    {
        if(!isset($this->view)) {
            throw new RuntimeException("Vous devez spécifier une vue à afficher en cas d'erreur");
        }

        return set_exception_handler([$this, 'exceptionHandler']);
    }

    /**
     * Intercepte les exeptions lancées
     * 
     * @param Throwable $ex
     * 
     * @return void
     */
    abstract public function exceptionHandler(Throwable $ex): void;

}
