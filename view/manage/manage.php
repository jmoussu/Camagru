<?php
include '../../controller/db_root_login.php';
include '../../controller/manage/settings.php';

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
	
$login = $_SESSION['login'];


if (isset($_POST['logout']))
{
	session_unset();
	session_destroy();
	session_start();
	header("Refresh:0");// Pour acctualiser sans avoir a resouscrire le formulaire
}
	$stmt = $db->prepare("SELECT sendmail FROM user WHERE login = :login");
	$stmt->bindValue(":login", $login, PDO::PARAM_STR);
	$res = $stmt->execute();
	$row = $stmt->fetch();
	$row = $row['sendmail'];
?>

<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Index_login</title>
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
	<link rel="stylesheet" type="text/css" href="../../css/extern.css">
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
				echo'<a href="view/user_creation.php"> <input type="button" value="CREE VOTRE COMPTE"> </a>';
			}
			if (isset($_SESSION['login']))
			{
				echo'<div class="dot"></div>
				<div class="imconected" style="display: inline-block; margin-left:5px; ">
				Vous etes connecte <a class="moncompte" href="./view/userpage.php?login='.$_SESSION['login'].'">'.$_SESSION['login'].'</a>
				</div> 
				</br>';
				echo '<input class= "log_bouton" type="submit" name="logout" value="Logout"/>';
				echo '<input class= "setting_bouton" type="submit" name="setting" value="Setting"/>';
			}
			?>
				<br />
			</form>
		</div>	
	<a href="../../view/cam.php" > <img class="cam" src="../../resources/img/cam.png" alt="cam"></a>
</div>
	<div class="center" style="color: black;">

	<p class='main_title'>Manage your account</p>
	<a href="manage.php"><button class="button_selected">Settings</button></a>
	<a href="manage_login.php"><button class="button">Change your Login</button></a>
	<a href="manage_email.php"><button class="button" >Change your Email</button></a>
	<a href="manage_pw.php"><button class="button" >Change your Password</button></a>
	<a href="manage_del.php" ><button class="button_delete" >Delete your Account</button></a>
	<div style="color: black;">
		<form id='settings.php' name="settings.php" action='manage.php' method='post' accept-charset='UTF-8'>
		<p>Voulez vous recevoir un email lorsque qu'un utilisateur commente vos photos ?</p>
		<input type="radio" id="yes" name="choice" value="oui" <?php if ($row == 1){echo"checked";}?>>
		<label for="yes">Oui</label>
		<input type="radio" id="no" name="choice" value="non" <?php if ($row == 0){echo"checked";}?>>
		<label for="non">Non</label>
		<br/>
		<br/>
		<input type="submit" name="submit" value="OK" style="width : 5%;"/>
		</form>
		</div>
	<a href="../../index.php"><button type="button" class="submit"><span>Index</button></span></a>
	</div>
<div class='footer'>
	<p>© camagru 42<p>
</div>
</body>
</html>




<!-- <html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Index_login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../css/extern.css">
</head>
<body>
	<div id="center" style="color: black;">
	<h1>Change your Email and Password<h1>
	<form id='manage.php' name="manage.php" action='manage_change.php' method='post' accept-charset='UTF-8'>
		<input class="text" placeholder="New e-mail" type="email" name="newlogin" value="" />
		<br />
		<input class="text" placeholder="New password" type="password" name="newpasswd" value="" />
		<input type="submit" name="submit" value="OK"/>

	</form>

	<h1>Delete your account<h1>
	<form id='manage.php' name="manage.php" action='manage_del.php' method='post' accept-charset='UTF-8'>

	<?php
	//  echo'<button type="submit" name="id" value="   '.$id.'   ">Delete your account</button>'
	 ?>

	</form>
	<a href="../index.php"><button type="button" >Index</button></a>
	</div>
</body>
</html> -->
