<?php
include '../../controller/manage/change_email.php';
if (isset($_POST['logout']))
{
	session_unset();
	session_destroy();
	session_start();
	header("Refresh:0");// Pour acctualiser sans avoir a resouscrire le formulaire
}
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
?>

<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Change Email</title>
	<link rel="stylesheet" type="text/css" href="../css/extern.css">
	<link rel="stylesheet" href="../../css/style.css">
	<link rel="stylesheet" href="../../css/manage.css">
	<link href='https://fonts.googleapis.com/css?family=Rubik' rel='stylesheet'>
</head>
<body>
<div class="banniere">
			<a href="../../index.php" style="text-decoration: none">
				<h1 id="nom-site">CAMAGRU</h1>
			</a>
			
			<div id="log">
				<form method="POST" action ="">
				<?php 
				if(!isset($_SESSION['login']))
				{
					echo '<input class= "log_bouton" type="text" name="login" value="" placeholder="Mail ou Login" required=""/>
					<br />
					<input class= "log_bouton" type="password" name="passwd" value="" placeholder="Mot de passe" required=""/>
					<br/>
					<input class="log_bouton" type="submit" name="submit" value="VOUS CONNECTER"/><br>';
					echo'<a href="../view/user_creation.php"> <input type="button" value="CREE VOTRE COMPTE"> </a>';
				}
				if (isset($_SESSION['login']))
				{
					echo'<div class="dot"></div>
					<div class="imconected" style="display: inline-block; margin-left:5px; ">
					Vous etes connecte <a class="moncompte" href="../view/userpage.php?login='.$_SESSION['login'].'">'.$_SESSION['login'].'</a>
					</div> 
					</br>';
					echo '<input class= "log_bouton" type="submit" name="logout" value="Logout"/>';
					echo '<input class= "setting_bouton" type="button" name="setting" value="Setting" onclick="window.location.href=\'manage.php\'"/>';
					
				}
				?>
					<br />
				</form>
			</div>
			
		<a href="../../view/cam.php" > <img class="cam" src="../../resources/img/cam.png" alt="cam"></a>
	</div>	
	<div class="center" style="color: black;">
	<p class='main_title'>Manage your account</p>
	<a href="manage.php"><button class="button">Settings</button></a>
	<a href="manage_login.php"><button class="button">Change your Login</button></a>
	<a href="manage_email.php"><button class="button_selected" >Change your Email</button></a>
	<a href="manage_pw.php"><button class="button" >Change your Password</button></a>
	<a href="manage_del.php" ><button class="button_delete" >Delete your Account</button></a>
	<div style="color: black;">
		<form id='manage.php' name="manage.php" action='manage_email.php' method='post' accept-charset='UTF-8'>
			<br />
			<input class="manage_text" placeholder="New E-mail" type="email" name="newmail" value="<?php echo isset($_POST['newmail']) ? $_POST['newmail'] : '' ?>" required=""/>
			<input class="manage_text" placeholder="New E-mail confirmation" type="email" name="newmailconf" value="<?php echo isset($_POST['newmailconf']) ? $_POST['newmailconf'] : '' ?>" required=""/>
			<input type="submit" name="submit" value="Token" placeholder="test" style="width : 5%;"/>
			<p>Un token sera envoyé sur votre nouvelle adresse. Connectez vous afin de récuperer et rentrez le token ci dessous</p>
			<input class="manage_text" placeholder="Token" type="text" name="token" value=""/>
			<input type="submit" name="submit" value="OK" style="width : 5%;"/>
		</form>
		<a href="../../index.php"><button type="button" class="submit"><span>Index</button></span></a>
		</div>
	</div>
<div class='footer'>
	<p>© camagru 42<p>
</div>
</body>
</html>
