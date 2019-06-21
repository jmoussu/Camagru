<?php
include '../controller/user.php';
session_start();
if ((isset($_SESSION['login'])))
{
	echo"
	<script> 
	 alert('Acces au invités seulement'); 
	 window.location='../index.php';
	 </script>";
	
	// header('Location: ../../index.php');
	exit();
}
if (isset($_POST['logout']))
{
	session_unset();
	session_destroy();
	session_start();
}
if (isset($_POST['submit']))
{
	// if (($_POST['login'] !== "") && ($_POST['email'] !== "") && ($_POST['passwd'] !== "") && ($_POST['passwd2'] !== ""))
	// {
	if (create_user($_POST['email'], $_POST['login'], $_POST['passwd'], $_POST['passwd2']) == true)
		echo "<p>Felicitation vous avez crée votre compte un mail de confirmation va vous etre envoyer</p>";
	else
		echo "<p>Erreur veuillez réessayer s'il vous plait</p>";
	// }

	// if (($_POST['login'] !== "")  && ($_POST['passwd'] === ""))
	// 	echo "<p>Veuillez rentrer un mot de passe svp</p>";
}
?>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Camagru</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/manage.css">
  <link href='https://fonts.googleapis.com/css?family=Rubik' rel='stylesheet'>
</head>
<body>
<div class="banniere">
		<a href="../index.php" style="text-decoration: none">
			<h1 id="nom-site">CAMAGRU</h1>
		</a>
</div>
	
<html>
<div class="center" style="color: black;">
		<p class ='main_title'>Créer votre compte</p>
		<p>Veuillez compléter ce formulaire. Un email de confirmation vous sera envoyé.</p>
		<form method="POST" action ='' accept-charset='UTF-8'>
				<input class="manage_text" type="text" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>" placeholder="Adresse email" required=""/>
					<br />
				<input class="manage_text" type="text" name="login" value="<?php echo isset($_POST['login']) ? $_POST['login'] : '' ?>" placeholder="Identifiant" required=""/>
					<br />
					
				<input class="manage_text" type="password" name="passwd" value="" placeholder="Mot de Passe" required=""/>
				<br />
				<input class="manage_text" type="password" name="passwd2" value="" placeholder="Confirmation mot de passe" required=""/>
					<br />
				<input type="submit" name="submit" value="OK" style="width : 5%;" href="/index.php"/>
					<br />
		</form>
		<a href="../index.php"><button type="button" class="submit"><span>Index</button></span></a>
</div>
<div class='footer'>
	<p>© camagru 42<p>
</div>
</html>
