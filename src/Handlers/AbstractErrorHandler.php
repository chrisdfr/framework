<?php

/**
 * AbstractErrorHandler.php
 * @Auteur: Christophe Dufour
 * 
 * Classe de base pour les gestionnaires d'erreurs
 */

declare(strict_types=1);

namespace ChrisDfr\Framework\Handlers;

use ChrisDfr\Framework\Application;
use ChrisDfr\Framework\ApplicationComponent;
use Deprecated;
use Throwable;

abstract class AbstractErrorHandler extends ApplicationComponent
{
    // Instance de la classe
    protected static array $instance = [];

    /**
     * Définit une fonction pour la gestion d'exceptions 
     * 
     * @param ?callable $callback
     * [optionnel] La fonction à appeler quand une exception se produit
     * @return ?callable
     */
    public static function setExceptionHandler(?callable $callback): ?callable
    {
        return set_exception_handler($callback);
    }

    /**
     * Intercepte les exeptions lancées
     * 
     * @param Throwable $ex
     * 
     * @return void
     */
    abstract public function exceptionHandler(Throwable $ex): void;

    /**
     * Ecrit les erreurs détectées dans un fichier
     * 
     * @return void
     */
    abstract protected function write(array $datas): void;

    /**
     * Affihche une erreur
     * 
     * @param Throwable $ex
     * 
     * @return void
     */
    abstract protected function display(Throwable $ex): void;

    /**
     * Crée et/ou retourne l'instance d'une classe qui hérite de AbstractErrorHandler
     * 
     * @param Application $app
     * 
     * @return AbstractErrorHandler
     */
    #[Deprecated("Méthode vouée à disparaître")]
    public static function init(Application $app): AbstractErrorHandler
    {
        $class = static::class;

        if(!isset(self::$instance[$class])) {
            self::$instance[$class] = new static($app);
        }

        return self::$instance[$class];
    }

}
