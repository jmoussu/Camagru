<?php
include '../controller/db_root_login.php';
include '../controller/user.php';
session_start();
if (!(isset($_SESSION['login'])))
{
	echo"
	<script> 
	 alert('Acces interdit au invités'); 
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
	header("Refresh:0");// Pour acctualiser sans avoir a resouscrire le formulaire
}
?>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Camagru</title>
  <link rel="stylesheet" href="../css/style.css">
  <link href='https://fonts.googleapis.com/css?family=Rubik' rel='stylesheet'>
	<script src="../controller/whiteboxuser.js"></script>
	<script src="../controller/scrolluser.js"></script>
	
</head>
<body>
<div class="banniere">
		<a href="../index.php" style="text-decoration: none">
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
				echo '<input class= "setting_bouton" type="button" name="setting" value="Setting" onclick="window.location.href=\'manage/manage.php\'"/>';
				
			}
			?>
				<br />
			</form>
		</div>
		
	<a href="../view/cam.php" > <img class="cam" src="../resources/img/cam.png" alt="cam"></a>
</div>

<?php
if (!isset($_GET['login']))
{
	if (!(isset($_GET['login']))) // ou n'est pas dans la base
	{
		echo"
		<script> 
		 alert('Page inexistante'); 
		 window.location='../index.php';
		 </script>";
		
		// header('Location: ../../index.php');
		exit();
	}
}
?>

<div class="Fildactu">
	<?php
	echo "<h2 id='". $_GET['login'] ."' class='title'>Profile de ". $_GET['login'] ."<h2>";
	?>
	<div class='imgscontainer' id='imgscontainer'>
	<?php
	$stmt = $db->prepare("SELECT id, path, date, nb_like, nb_comment FROM pic WHERE user = :user ORDER BY date DESC LIMIT 0, 15");
	$stmt->bindValue(':user', $_GET['login'], PDO::PARAM_STR);
	$stmt->execute();
	while ($data = $stmt->fetch())
	{
		echo "<div class='img_previw'>
		<div class='imgdetail2'>
			<div class='likecom'>
				<div class='containerlikecom'id='containercom".$data['id']."'>
					<img class='coeur_com' src='../resources/img/comment-icon.png' alt='C'>".$data['nb_comment']."
				</div>
				<div class='containerlikecom' id='containerlike".$data['id']."'>
					<img class='coeur_com' src='../resources/img/coeurP.png' alt='C'>". $data['nb_like']."
				</div>
			</div>
		</div>
		<img class='fil' id='".$data['id']."' src='".$data['path']."' alt='Pic' onclick='enlarge(this)'>
	</div>";
	}
	?>
	</div>

</div>
<div class='footer'>
	<p>© camagru 42<p>
</div>
<!-- WHITEBOX WHITEBOX WHITEBOX WHITEBOX WHITEBOX -->

<div id="id01" class="modal">

	<div class="modal-content animate">
		<div class="imgcontainer">
			<span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
		<div id="bigview">
	</div>	
	<div class="containerWB1">
		<input type="image" alt="coeur" class="coeur_comW" id="coeurW" src="../resources/img/coeurP.png" onclick="addlike()">
		<div id='containerlikeW'></div>
		<input type="image" alt="comment" class="coeur_comW" src="../resources/img/comment-icon.png" onclick="">
		<div id='containercomW'></div>
		<textarea id="myTextarea" rows=“15” cols=“60" minlength=“10” maxlength=“20" name="comment" placeholder="Your comment here..."></textarea>
		<input id="send" class="send" type="image" src="../resources/img/send.png" alt="send" onclick="addcom()">
	</div>
	<div id="coms_container" class="coms_container"></div>
</div>

</body>
</html>
