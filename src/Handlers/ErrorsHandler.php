<?php

/**
 * ErrorHandler.php
 * @Auteur: Christophe Dufour
 * 
 * Gère les erreurs du frameword et les enregistre
 */

declare(strict_types=1);

namespace ChrisDfr\Framework\Handlers;

use ChrisDfr\Framework\View\View;
use DateTime;
use SplFileObject;
use Throwable;

class ErrosHandler extends Error
{
    /**
     * Intercepte les exeptions lancées
     * 
     * @param Throwable $ex
     * 
     * @return void
     */
    public function exceptionHandler(Throwable $ex): void
    {
        $time = new DateTime();

        if(!is_null($this->filename)) {
            $file = new SplFileObject($this->filename, "a+");

            $string = $time->format("[Y-m-d à H:i]") . " [" . get_class($ex) . "] Fichier: " . 
                    basename($ex->getFile()) . " (Ligne " . $ex->getLine() . ")" . PHP_EOL . 
                    "Message: " . $ex->getMessage() . PHP_EOL;

            $file->fwrite($string);
        }
        
        $string = get_class($ex) . ": " . $ex->getMessage() . " in " . basename($ex->getFile()) . ":" . $ex->getLine();

        $view = new View($this->app);
        $view->viewFile = $this->view;
        $view->setData('error', $string);

        $this->app->httpResponse->view = $view;
        $this->app->httpResponse->render();
    }

}
