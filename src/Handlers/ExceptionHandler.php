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
use Throwable;
use ValueError;

abstract class ExceptionHandler extends ApplicationComponent
{
    // Nom du fichier de log
    public string|null $filename = null {
        set {
            if(empty($value)) {
                throw new ValueError("Le nom du fichier de lon ne peut pas être une chaine de caractère vide");
            }

            $this->filename = $value;
        }
    }

    /**
     * Définit une fonction pour la gestion d'exceptions 
     * 
     * @return ?callable
     */
    public function setExceptionHandler(): ?callable
    {
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
