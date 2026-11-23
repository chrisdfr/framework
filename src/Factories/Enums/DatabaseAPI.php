<?php

/**
 * DatabaseAPI.php
 * @Auteur: Christophe Dufour
 * 
 * API disponible pour une conneion à une base de données
 */
namespace ChrisDfr\Framework\Factories\Enums;

enum DatabaseAPI: string
{
    case PDO = "PDO";
}
