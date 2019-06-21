<?php
include '../../controller/db_root_login.php';
include '../../controller/user.php';

session_start();

if (!(isset($_SESSION['login'])))
{
	echo"
	<script> 
	alert('Acces interdit au invités'); 
	window.location='../../index.php';
	</script>";
	
	// header('Location: ../../index.php');
	exit();
}
if (isset($_POST['submit']) && $_POST['submit'] == 'OK')
{

	$stmt = $db->prepare("DELETE FROM user WHERE login = :login");
	$stmt->bindValue(':login', $_SESSION['login'], PDO::PARAM_STR);
	$stmt->execute();

	session_unset();
	session_destroy();
	session_start();
	echo"
	<script> 
	alert('Votre compte a bien été supprimer'); 
	window.location='../../index.php';
	</script>";
}
?>
