<?php

// Fuseau horraire 
date_default_timezone_set('Europe/Paris');

// Session
session_start();

// Connexion a la BDD 
$pdo = new PDO(
    'mysql:host=localhost;dbname=veville',
    'root',
    '',
    array(
        PDO:: ATTR_ERRMODE => PDO::ERRMODE_WARNING,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    )
);

// Constante de site
define('URL','');
define('SALT','Comp!iqu3');

// Initialissation de variables 
$content = '';
$left_content='';
$right_content='';

// Inclure du fichier de fonctions
require_once('functions.php');
