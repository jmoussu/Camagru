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
	$pw = $_POST['newpw'];
	$cpw = $_POST['newpwconf'];
	$login = $_SESSION['login'];
	$e = 0;
	
	if (!($pw) || (strlen($pw) == 0))
	{
		echo "<p>Veuillez entrer un Mot de passe s'il vous plait</p>";
		$e = 1;
	}

	if (((strlen($pw) < 6) || (strlen($pw) > 16)) && (strlen($pw) != 0))
	{
		echo "<p>Veuillez entrer un Mot de passe avec entre 6 et 16 caractères s'il vous plait</p>";
		$e = 1;
	}

	elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).*$/', $pw))
	{
		echo "<p>Veuillez entrer un Mot de passe avec au moins 1 minuscule, 1 majuscule et 1 chiffre</p>";
		$e = 1;
	}
	
	if (auth($login, $_POST['password']) == false)
	{
		echo "<p>Mauvais Mot de Passe de connection !</p>";
		$e = 1;
	}

	if ($pw != $cpw)
	{
		echo "<p>Vos 2 mots de passes ne coresspondent pas !</p>";
		$e = 1;
	}

	if ($e == 0)
	{
		$hashpw = hash('whirlpool', $pw);
		$stmt = $db->prepare("UPDATE user SET password = :hashpw WHERE login = :login ");
		$stmt->bindValue(':hashpw', $hashpw, PDO::PARAM_STR);
		$stmt->bindValue(':login', $login, PDO::PARAM_STR);
		$stmt->execute();
		echo "<p>Bravo vous avez changer votre Mot de Passe !</p>";
	}

}








// $resultat = mysqli_query($db, 'SELECT id, email FROM users');
// while($donnees = mysqli_fetch_assoc($resultat))
// {
// 	if ($donnees['email'] == $login)
// 	{
// 		$id = $donnees['id'];
// 		break;
// 	}
// }
// mysqli_free_result($resultat);

// if (!(isset($id)))
// {
// 	header('Location: ../index.php');
// }
// echo "$id";
?>
