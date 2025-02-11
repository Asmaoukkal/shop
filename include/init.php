<?php
//  -------------CONNEXION BDD
$connect_db = new PDO('mysql:host=localhost;dbname=shop', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
]);
//echo '<pre>'; var_dump($connect_db); echo '</pre>';

//-------------------SESSION

session_start();

//-------------------CHEMIN

//echo '<pre>'; var_dump($_SERVER); echo '</pre>';
define('RACINE_SITE', $_SERVER['DOCUMENT_ROOT'] . '/');
//echo '<pre>'; print_r(RACINE_SITE); echo '</pre>';

//Lors de l'enregestriment d'image/photos, nous aurrons besoin du chemin complet de dossier images pour enregistrer la photo
//echo RACINE_SITE .'shop/assets/images/product.jpg';

define("URL", "http://localhost/PHP/shop/");
// Cette constante servira a enregistrer l'URL d'une photo/image dans la BDD on en pas conserver la photo physique dans la BDD, donc on defenit une URLvers le bon dossier

//-------------------- Variable 

$content = '';

//---------------------FAILLES XSS
 foreach($_POST as $key => $value){
    $_POST[$key] = htmlentities(addslashes(trim($value)));
 }
 foreach($_GET as $key => $value){
    $_GET[$key] = htmlentities(addslashes(trim($value)));
 }
// trim() : fonction predefenier qui supprime les espaces en debut et fin de chaines de caractaire

 //------------------- INCLUSION FONCTION

 require_once("functions.php");

 
?>