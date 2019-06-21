<?php
include 'token.php';
function auth($login, $passwd){
	include 'db_root_login.php';
	if	(!($login) || !($passwd))
		return(FALSE);

	$passwd = hash("whirlpool", $passwd);
	// $USER["login"] = $login;

	$valide = 0;
	$good_info = 0;
	$resultatdeco = $db->query('SELECT login, password, mail, VALIDE FROM user');

	while ($donnees = $resultatdeco->fetch())
	{
		if (($login == $donnees['login'] || $login == $donnees['mail']) && $passwd == $donnees['password'])
		{
			$good_info = 1;
			$_SESSION['login'] = $donnees['login'];
			$valide = $donnees['VALIDE'];
			$mail = $donnees['mail'];
		}
	}
	$path = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '/', 1)); // '/Camagru_OurGit'
	if ($good_info != 1 && $_SERVER['PHP_SELF'] == "$path/index.php" )
	{
		echo "Mauvaise information de connexion"." ";
		echo "<a href='./view/manage/forgot_pwd.php'>Mot de passe oublié ?</a>";
		return(FALSE);
	}
	if ($good_info != 1 && $_SERVER['PHP_SELF'] != "$path/index.php" )
	{
		return(FALSE);
	}

	if ($valide == 0)
	{
		echo "Merci de cliquer sur le lien de validation qui vous as été envoyer par mail à ".$mail." pour vous connecter !";
		session_unset();
		session_destroy();
		session_start();
		return(FALSE);
	}

	return(TRUE);

}

function create_user($mail, $login, $passwd, $passwd2){
	include 'db_root_login.php';

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

	if (!($passwd) || (strlen($passwd) == 0))
	{
		echo "<p>Veuillez entrer un Mot de passe s'il vous plait</p>";
		$e = 1;
	}
	if (((strlen($passwd) < 6) || (strlen($passwd) > 16)) && (strlen($passwd) != 0))
	{
		echo "<p>Veuillez entrer un Mot de passe avec entre 6 et 16 caractères s'il vous plait</p>";
		$e = 1;
	}
	elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).*$/', $passwd))
	{
		echo "<p>Veuillez entrer un Mot de passe avec au moins 1 minuscule, 1 majuscule et 1 chiffre</p>";
		$e = 1;
	}

	if ($passwd2 != $passwd)
	{
		echo "<p>Votre Confirmation de mot de passe est fausse</p>";
		$e = 1;
	}



	$tab = [
		"mail" => $mail,
		"passwd" => hash('whirlpool', $passwd) 
	];
	
	$resultat = $db->query('SELECT mail FROM user');
	while ($donnees = $resultat->fetch())
	{
		if ($donnees['mail'] == $tab['mail'])
		{
			echo"<p>Cette E-Mail existe deja\n</p>";
			$e = 1;
		}
	}
	
	$resultatlogin = $db->query('SELECT login FROM user');
	while ($donnees = $resultatlogin->fetch())
	{
		if ($donnees['login'] == $login)
		{
			echo"<p>Ce Login existe deja\n</p>";
			$e = 1;
		}
	}

	if ($e == 1)
		return(false);

	sleep(1);
	$token = token();
	$stmt = $db->prepare("INSERT INTO user (login, password, mail, VALIDE, sendmail, token) VALUES (:login, :password, :mail, :VALIDE, :sendmail, :token)");
	$stmt->bindValue(':login', $login, PDO::PARAM_STR);
	$stmt->bindValue(':password', $tab['passwd'], PDO::PARAM_STR);
	$stmt->bindValue(':mail', $tab['mail'], PDO::PARAM_STR);
	$stmt->bindValue(':VALIDE', 0, PDO::PARAM_INT);
	$stmt->bindValue(':sendmail', 1, PDO::PARAM_INT);
	$stmt->bindValue(':token', $token, PDO::PARAM_STR);
	$stmt->execute();
	// SEE ERROR WITH THAT
	// if (!$stmt->execute()) {
	// 	print_r($stmt->errorInfo());
	// }
	$path = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '/', 1)); // '/Camagru_OurGit'
	$to_email = $tab['mail'];
	$subject = 'Bienvenue sur Insta Camagru Confirmation de votre Compte';
	$message = "Bonjour,\n Pour valider votre compte Cliquer sur ce lien \n http://".$_SERVER['HTTP_HOST'].$path."/view/validation.php?val=".$token." \n Merci et Bienvenue !";
	$headers = 'From: noreply@camagru.com';
	mail($to_email,$subject,$message,$headers);
	return(true);
}

function mod_user($login, $va2){
	include 'db_root_login.php';
	if($login == "" || $va2 == "")
	{
		return false;
	}
	else
	{
		
		$npasswd = $va2;
		$hashpw = hash('whirlpool', $va2);
		$stmt = $db->prepare("UPDATE user SET password = :hashpw WHERE login = :login ");
		$stmt->bindValue(':hashpw', $hashpw, PDO::PARAM_STR);
		$stmt->bindValue(':login', $login, PDO::PARAM_STR);
		$stmt->execute();

		
		return true;
	}
}
// function whoami($login){
	
// }

// function mod_root($login, $passwd){
// 	if($login == "" || $passwd == ""){
// 		return false;
// 	}
// 	else{
		
// 		$npasswd = $passwd;
// 		$hashnpw = hash("sha512", $npasswd);
// 		$user["login"] = $login;
// 		$i = 0;
		
// 		$top_user = unserialize(file_get_contents("./resources/database/db_user"));
// 		if ($top_user) {
// 			foreach ($top_user as $arg){
// 				if ($arg['login'] == $login){	
// 						$top_user[$i]["passwd"] = $hashnpw;
// 						file_put_contents("./resources/database/db_user", serialize($top_user));
// 						return true;
// 				}
// 				$i++;
// 			}
// 		}
// 		return false;
// 	}
// }

?>
