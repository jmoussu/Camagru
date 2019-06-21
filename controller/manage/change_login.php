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
	$login = $_POST['newlogin'];
	$clogin = $_POST['newloginconf'];
	// $login = $_SESSION['login'];
	$e = 0;

	if (!($login) || (strlen($login) == 0))
	{
		echo "<p>Veuillez entrer un Login s'il vous plait</p>";
		$e = 1;
	}

	if (isset($login) && (strlen($login) < 3 && (strlen($login) != 0)))
	{
		echo "<p>Veuillez entrer un Login avec au moins 3 caractères s'il vous plait</p>";
		$e = 1;
	}
	
	if (isset($login) && (strlen($login) > 20 && (strlen($login) != 0)))
	{
		echo "<p>Veuillez entrer un Login de moins de 20 caractères s'il vous plait</p>";
		$e = 1;
	}
	if (isset($login) && !preg_match('/^[a-z\d_-]*$/i', $login))
	{
		echo "<p>Veuillez entrer un login ne contenant pas de caractères spéciaux</p>";
		$e = 1;	
	}
	
	if (auth($_SESSION['login'], $_POST['password']) == false)
	{
		echo "<p>Mauvais Mot de Passe !</p>";
		$e = 1;
	}

	if ($login != $clogin)
	{
		echo "<p>Le Login de confirmation ne corespond pas</p>";
		$e = 1;
	}
	
	if ($e == 0)
	{
		$stmt = $db->prepare("SELECT login FROM user WHERE login = :login");
		$stmt->bindValue(':login', $login, PDO::PARAM_STR);
		$res = $stmt->execute();
		$row = $stmt->fetch();
		if (!empty($row))
		{
			echo"<p>Le Login ne peut pas être changer, il est déjà utiliser</p>";
			$e = 1;
		}
	}

	if ($e == 0)
	{
		
		$stmt = $db->prepare("UPDATE user SET login = :login WHERE login = :oldlogin ");
		$stmt->bindValue(':login', $login, PDO::PARAM_STR);
		$stmt->bindValue(':oldlogin', $_SESSION['login'], PDO::PARAM_STR);
		$stmt->execute();
		$stmt = $db->prepare("UPDATE tab_comment SET user = :login WHERE user = :oldlogin");
		$stmt->bindValue(':login', $login, PDO::PARAM_STR);
		$stmt->bindValue(':oldlogin', $_SESSION['login'], PDO::PARAM_STR);
		$stmt->execute();
		$stmt = $db->prepare("UPDATE pic SET user = :login WHERE user = :oldlogin");
		$stmt->bindValue(':login', $login, PDO::PARAM_STR);
		$stmt->bindValue(':oldlogin', $_SESSION['login'], PDO::PARAM_STR);
		$stmt->execute();
		$stmt = $db->prepare("UPDATE tab_like SET login = :login WHERE login = :oldlogin");
		$stmt->bindValue(':login', $login, PDO::PARAM_STR);
		$stmt->bindValue(':oldlogin', $_SESSION['login'], PDO::PARAM_STR);
		$stmt->execute();
		session_unset();
		session_destroy();
		session_start();
		echo "<p>Bravo vous avez changer votre Login en ".$login."</p>";
		$_SESSION['login'] = $login;
	}
}
?>
