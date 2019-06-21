<?php
$path = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '/', 1)); // '/Camagru_OurGit'
include $_SERVER['DOCUMENT_ROOT'].$path.'/config/database.php';
try
{
	$db = new PDO($DB_DSN_TABLE, $DB_USER, $DB_PASSWORD);
	// $db = new PDO('mysql:host=localhost;dbname=camagru;charset=utf8', 'root', 'admin123');
}
catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}
?>
