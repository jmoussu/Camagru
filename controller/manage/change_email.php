<?php
include '../../controller/db_root_login.php';
include '../../controller/user.php';
include '../../controller/token4.php';

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
if (isset($_POST['submit']) && $_POST['submit'] == 'Token')
{
	$to_email = $_POST["newmail"];
	$e = 0;
	if (strlen($to_email) == 0)
	{
		echo "Veuillez rentrer un email";
		$e = 1;
	}
	if (!(preg_match(" /^.+@.+\.[a-zA-Z]{2,}$/ ", $to_email)) && (strlen($to_email) != 0))
	{
		echo "<p>Veuillez entrer un E-mail valide s'il vous plait</p>";
		$e = 1;
	}
	if ($_POST["newmail"] != $_POST["newmailconf"])
	{
		echo "Les deux emails ne correspondent pas";
		$e = 1;
	}
	if ($e == 0)
	{
		$token = token4();
		$path = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '/', 1)); // '/Camagru_OurGit'
		$stmt = $db->prepare("UPDATE user SET token = :token WHERE login = :login");
		$stmt->bindValue(':token', hash('whirlpool', $token), PDO::PARAM_STR);
		$stmt->bindValue(':login', $_SESSION['login'], PDO::PARAM_STR);
		$stmt->execute();
		$subject = "Camagru - Changement d'adresse mail";
		$message = "Bonjour,\nNous avons enregistré votre demande de changement de mail, voici le token a saisir:\n $token\n\n\n
		Vous n'etes pas l'auteur de cette demande ? Votre compte a peut etre été piraté, veuillez changer de mot de passe :\n
		http://".$_SERVER['HTTP_HOST'].$path."/view/manage/manage_pw.php";
		$headers = 'From: noreply@camagru.com';
		mail($to_email,$subject,$message,$headers);
	}
}
if (isset($_POST['submit']) && $_POST['submit'] == 'OK')
{
	$mail = $_POST['newmail'];
	$cmail = $_POST['newmailconf'];
	$login = $_SESSION['login'];
	$stmt = $db->prepare("SELECT token FROM user WHERE login = :login");
	$stmt->bindValue(':login', $login, PDO::PARAM_STR);
	$res = $stmt->execute();
	$row = $stmt->fetch();
	$e = 0;

	if (!($mail) || (strlen($mail) == 0))
	{
		echo "<p>Veuillez entrer un E-Mail s'il vous plait</p>";
		$e = 1;
	}

	if (!(preg_match(" /^.+@.+\.[a-zA-Z]{2,}$/ ", $mail)) && (strlen($mail) != 0))
	{
		echo "<p>Veuillez entrer un E-mail valide s'il vous plait</p>";
		$e = 1;
	}

	// if (auth($login, $_POST['password']) == false)
	// {
	// 	echo "<p>Mauvais Mot de Passe !</p>";
	// 	$e = 1;
	// }

	if ($_POST['token'] == '')
	{
		echo "<p>Veuillez entrer le token reçu sur la nouvelle adresse mail s'il vous plait</p>";
		$e = 1;
	}
	if (hash('whirlpool', $_POST['token']) != $row['token'])
	{
		echo $_POST['token'].$row['token'];
		echo "<p>Mauvais token</p>";
		$e = 1;
	}

	if ($mail != $cmail)
	{
		echo "<p>L'E-Mail de confirmation ne corespond pas</p>";
		$e = 1;
	}
	
	if ($e == 0)
	{
		$stmt = $db->prepare("SELECT mail FROM user WHERE mail = :mail");
		$stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
		$res = $stmt->execute();
		$row = $stmt->fetch();
		// print_r($row);
		if (!empty($row))
		{
			echo"<p>L'E-Mail ne peut pas être changer, il est déjà utiliser</p>";
			$e = 1;
		}
	}

	if ($e == 0)
	{
		
		$stmt = $db->prepare("UPDATE user SET mail = :mail WHERE login = :login ");
		$stmt->bindValue(':login', $login, PDO::PARAM_STR);
		$stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
		$stmt->execute();
		echo "<p>Bravo vous avez changé votre adresse E-Mail</p>";
		$stmt = $db->prepare("UPDATE user SET token = '' WHERE login = :login ");
		$stmt->bindValue(':login', $login, PDO::PARAM_STR);
		$stmt->execute();
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
