<?php
include '../controller/db_root_login.php';


$e = 0;

if (!isset($_GET['val']))
{
	echo"Erreur pas de token pour la validation verifier le lien / mail";
	$e = 1;
}

$stmt = $db->prepare("SELECT token FROM user WHERE token = :token");
$stmt->bindValue(':token', $_GET['val'], PDO::PARAM_STR);
$res = $stmt->execute();
$row = $stmt->fetch();
if (empty($row))
{
	echo"Mauvais token de validation verifier le lien / mail";
	$e = 1;
}
if ($e == 0)
{
	$stmt = $db->prepare("UPDATE user SET VALIDE = 1 WHERE token = :token");
	$stmt->bindValue(':token', $_GET['val'], PDO::PARAM_STR);
	$res = $stmt->execute();
	$stmt = $db->prepare("UPDATE user SET token = '' WHERE token = :token");
	$stmt->bindValue(':token', $row['token'], PDO::PARAM_STR);
	$stmt->execute();
	echo "Compte valide";
}
?>
