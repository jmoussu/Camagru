<?php
include '../controller/db_root_login.php';
include 'user.php';

$e = 0;

if (!isset($_GET['reset']))
{
	echo "Erreur pas de token pour renouveller le mot de passe";
	$e = 1;
}

$stmt = $db->prepare("SELECT token FROM user WHERE token = :token");
$stmt->bindValue(':token', hash('whirlpool', $_GET['reset']), PDO::PARAM_STR);
$res = $stmt->execute();
$row = $stmt->fetch();
if (empty($row))
{
	echo"Mauvais token de validation verifier le lien / mail";
	$e = 1;
}
if ($e == 0)
{
	echo "<meta http-equiv='refresh' content='0;URL=../view/manage/reset_pwd.php?reset=".$_GET['reset']."'>";
	// echo "<meta http-equiv='refresh' content='0;URL=view/user_creation.php'>";

	// $stmt = $db->prepare("UPDATE user SET token = '' WHERE token = :token");
	// $stmt->bindValue(':token', $row['token'], PDO::PARAM_STR);
	// $stmt->execute();
	echo "Compte valide";
}
?>
