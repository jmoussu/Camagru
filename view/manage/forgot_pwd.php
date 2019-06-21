<?php
include '../../controller/db_root_login.php';
include '../../controller/user.php';
session_start();
if ((isset($_SESSION['login'])))
{
	echo"
	<script> 
	 alert('Acces au invités seulement'); 
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
	<title>Index_login</title>
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
			
			
		<a href="../../view/cam.php" > <img class="cam" src="../../resources/img/cam.png" alt="cam"></a>
	</div>
	<div class="center" style="color: black;">
    <p class="main_title">Entrez le login de votre compte</p>

    <form id='../../controller/manage/reset_password.php' name="manage.php" action='../../controller/manage/reset_password.php' method='post' accept-charset='UTF-8'>
			<input class="manage_text" placeholder="Login" type="elogin" name="login" value="<?php echo isset($_POST['login']) ? $_POST['login'] : '' ?>" required=""/>
            <input class="submit" type="submit" name="submit" value="OK" style="width : 5%;"/>
    </form>
    <a href="../../index.php"><button type="button" class="submit"><span>Index</button></span></a>
		</div>
	</div>
<div class='footer'>
	<p>© camagru 42<p>
</div>
</body>
</html>
