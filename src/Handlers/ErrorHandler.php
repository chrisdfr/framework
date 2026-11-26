<?php

/**
 * ErrorHandler.php
 * @Auteur: Christophe Dufour
 * 
 * Gère les erreurs du frameword et les enregistre
 */

declare(strict_types=1);

namespace ChrisDfr\Framework\Handlers;

use ChrisDfr\Framework\Application;
use ChrisDfr\Framework\View\View;
use Throwable;

class ErrorHandler extends AbstractErrorHandler
{
    // Le nom du fichier de log
    private string $filename;

    /**
     * Constructeur
     * 
     * @param Application $app
     */
    protected function __construct(Application $app)
    {
        parent::__construct($app);

        $this->filename = $this->app->rootDir . str_replace("/", DIRECTORY_SEPARATOR, "/log/errors.log");
        $this->setExceptionHandler([$this, 'exceptionHandler']);
    }

    /**
     * Intercepte les exeptions lancées
     * 
     * @param Throwable $ex
     * 
     * @return void
     */
    public function exceptionHandler(Throwable $ex): void
    {
        $time = new \DateTime();

        $datas = array(
            'time' => $time->format("[Y-m-d à H:i]"),
            'errorType' => get_class($ex),
            'file' => basename($ex->getFile()),
            'line' => $ex->getLine(),
            'message' => $ex->getMessage()
        );

        $this->write($datas);
        $this->display($ex);
    }

    /**
     * Ecrit les erreurs détectées dans un fichier
     * 
     * @return void
     */
    protected function write(array $datas): void
    {
		$file = new \SplFileObject($this->filename, "a+");

        $string = $datas['time'] . " [" . $datas['errorType'] . "] Fichier: " . 
                $datas['file'] . " (Ligne " . $datas['line'] . ")" . PHP_EOL . 
                "Message: " . $datas['message'] . PHP_EOL;

        $file->fwrite($string);
    }
    
    /**
     * Affiche une vue d'erreur
     * 
     * @param \Throwable $exception
     * 
     * @return void
     */
    protected function display(\Throwable $exception): void
    {
        $view = new View($this->app);

        if(DisplayErrors::displayErrors()) {
            $view->viewFile = "detectedError";
            $view->setData('error', $exception);
        }
        else {
            $view->viewFile = "unknownError";
        }

        $this->app->httpResponse->view = $view;
        $this->app->httpResponse->render();
    }

}
