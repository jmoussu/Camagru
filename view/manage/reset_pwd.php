<?php
include '../../controller/db_root_login.php';
include '../../controller/user.php';
if (isset($_GET['reset']))
{
    $_POST['reset'] = $_GET['reset'];
}

if (isset($_POST['submit']) && isset($_POST['newpw']) && isset($_POST['newpwconf']))
{
	$e = 0;
	if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,16}$/', $_POST['newpw']))
		{
			echo "<p>Veuillez entrer un Mot de passe avec au moins 1 minuscule, 1 majuscule et 1 chiffre</p>";
			$e = 1;
		}
	if ($_POST['newpw'] != $_POST['newpwconf'])
	{
		echo "<p>Les 2 mots de passe ne correspondent pas</p>";
		$e = 1;
	}
	if (strlen($_POST['reset'] < 2))
	{
		echo "<p>Erreur token</p>";
		$e = 1;
	}
	if ($e == 0)
	{	
		$stmt = $db->prepare("SELECT login FROM user WHERE token = :token");
		$stmt->bindValue(':token', hash('whirlpool', $_POST['reset']), PDO::PARAM_STR);
		$res = $stmt->execute();
		$row = $stmt->fetch();
		mod_user($row['login'], $_POST['newpw']);
		$stmt = $db->prepare("UPDATE user SET token = '' WHERE token = :token");
		$stmt->bindValue(':token', hash('whirlpool', $_POST['reset']), PDO::PARAM_STR);
		$res = $stmt->execute();
		$row = $stmt->fetch();
		$_POST['reset'] = "";
		echo "<p>Votre mot de passe a bien été changé</p>";
		sleep (3);
		echo '<meta http-equiv="refresh" content="0;URL=../../index.php">';
	}
}

?>

<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Change Password</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
		<a href="../../view/cam.php" > <img class="cam" src="../../resources/img/cam.png" alt="cam"></a>
	</div>	
	<div class="center" style="color: black;">
	<p class='main_title'>Enter your new password</p>
	<div style="color: black;">
		<form id='reset_pwd.php' name="reset_pwd.php" action='reset_pwd.php' method='post' accept-charset='UTF-8'>
			<input class="manage_text" placeholder="New Password" type="password" name="newpw" value="" required=""/>
			<input class="manage_text" placeholder="New Password confirmation" type="password" name="newpwconf" value="" required=""/>
            <input type="hidden" name="reset" value="<?php if (isset($_POST['reset'])){echo $_POST['reset'];}?>">
			<br />
			<input type="submit" name="submit" value="OK" style="width : 5%;"/>
		</form>
		<a href="../../index.php"><button type="button" class="submit"><span>Index</button></span></a>
		</div>
</body>
</html>
