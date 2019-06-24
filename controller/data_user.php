<?php
include '../controller/db_root_login.php';
include '../controller/user.php';
session_start();

if (isset($_GET['start']) && isset($_GET['limit']) && isset($_GET['user'])) // SCROLL USERPAGE INFINI LOAD IMAGE SCROLLUSER.JS
{
	$s = (int)$_GET['start'];
	$l = (int)$_GET['limit'];
	$user = $_GET['user'];
	$stmt = $db->prepare("SELECT id, user, path, date, nb_like, nb_comment FROM pic WHERE user = :user ORDER BY date DESC LIMIT :s, :l");
	$stmt->bindValue(':s', $s, PDO::PARAM_INT);
	$stmt->bindValue(':l', $l, PDO::PARAM_INT);
	$stmt->bindValue(':user', $_GET['user'], PDO::PARAM_STR);
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
}
if (isset($_GET['start2']) && isset($_GET['limit2'])) //CAM SCROLL INFINI LOAD IMAGE SCROLLUSER.JS
{
	$s = (int)$_GET['start2'];
	$l = (int)$_GET['limit2'];
	$user = $_SESSION['login'];
	$stmt = $db->prepare("SELECT id, user, path, date, nb_like, nb_comment FROM pic WHERE user = :user ORDER BY date DESC LIMIT :s, :l");
	$stmt->bindValue(':s', $s, PDO::PARAM_INT);
	$stmt->bindValue(':l', $l, PDO::PARAM_INT);
	$stmt->bindValue(':user', $user, PDO::PARAM_STR);
	$stmt->execute();
	while ($data = $stmt->fetch())
	{
		echo "<div class='img_previw'>
		<div class='imgdetail1'> <img id='croix".$data['id']."' class='croix' src='../resources/img/croix.png' alt='X' onclick='sup(this)'></div>
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
}
elseif (isset($_GET['like'])) // LOAD LIKE WHITEBOX.JS
{
	$id = $_GET['like'];
	$stmt = $db->prepare("SELECT nb_like FROM pic WHERE id = :id");
	$stmt->bindValue(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
	$resultat2 = $stmt->fetch();
	exit($resultat2['nb_like']);
}
elseif (isset($_GET['likepic'])) // LOAD LIKE WHITEBOX.JS
{
	$id = $_GET['likepic'];
	$stmt = $db->prepare("SELECT id_pic, `login` FROM tab_like WHERE id_pic = :id_pic AND `login` = :user");
	$stmt->bindValue(':id_pic', $id, PDO::PARAM_INT);
	$stmt->bindValue(':user', $_SESSION['login'], PDO::PARAM_STR);
	$stmt->execute();
	$resultat = $stmt->fetch();
	if (isset($resultat) && $resultat != NULL){
		exit("../resources/img/coeurP.png");
	}
	else{
		exit("../resources/img/coeurV.png");
	}
}
elseif (isset($_GET['idnbcom'])) // LOAD nbCOM WHITEBOX.JS
{
	//comter le nb de com
	$id = $_GET['idnbcom'];
	$stmt = $db->prepare("SELECT count(id) FROM tab_comment WHERE id_pic = :id_pic");
	$stmt->bindValue(':id_pic', $id, PDO::PARAM_INT);
	$stmt->execute();
	$nbcom = $stmt->fetch();
	//Changer la nb de com table pic
	$new_nbcom = $nbcom['count(id)'];
	$stmt = $db->prepare("UPDATE `pic` SET `nb_comment`= :new_nbcom WHERE id = :id_pic");
	$stmt->bindValue(':id_pic', $id, PDO::PARAM_INT);
	$stmt->bindValue(':new_nbcom', $new_nbcom, PDO::PARAM_INT);
	$stmt->execute();
	exit($new_nbcom);
}
elseif (isset($_POST['com_start']) && isset($_POST['com_limit']) && isset($_POST['pic_id'])) // afficher les com petit a petit.
{
	$s = (int)$_POST['com_start'];
	$l = (int)$_POST['com_limit'];
	$id = $_POST['pic_id'];
	$stmt = $db->prepare("SELECT id, `date`, user, comment FROM tab_comment WHERE id_pic = :id_pic ORDER BY date DESC LIMIT :s, :l");
	$stmt->bindValue(':s', $s, PDO::PARAM_INT);
	$stmt->bindValue(':l', $l, PDO::PARAM_INT);
	$stmt->bindValue(':id_pic', $id, PDO::PARAM_INT);
	if (!$stmt->execute()){
		$error = implode( ", ", $stmt->errorInfo() );
		exit($error);
	}
	$x = 0;
	while ($data = $stmt->fetch())
	{
		if ($x == 1)
			$x = 0;
		else
			$x = 1;
		echo "<div class='com_container".$x."' id='".$data['id']."'>
		<div class='author_date_com'>".$data['user'].", le ".$data['date'].":</div> <hr>
			<div class='txt_com'>".nl2br($data['comment'])."</div>
			</div> </br>";
	}
	echo "<input id='input_load_com' class='load_bouton' type='button' value='Load more comments' onclick='getComment(".$id.")'>";
	exit();
}
elseif (isset($_GET['addlike'])) // ADD LIKE UPDATE TABLE LIKE ET PIC WHITEBOX.JS
{
	if (!(isset($_SESSION['login'])))
	{
		exit("notlog");
	}
	else
	{
		$id = $_GET['addlike'];
		// echo"$id";
		// 1 ragerder si dans la table il y a une ligne avec id_pic + user
		$stmt = $db->prepare("SELECT id_pic, `login` FROM tab_like WHERE id_pic = :id_pic AND `login` = :user");
		$stmt->bindValue(':id_pic', $id, PDO::PARAM_INT);
		$stmt->bindValue(':user', $_SESSION['login'], PDO::PARAM_STR);
		$stmt->execute();
		$resultat = $stmt->fetch();
		if (isset($resultat) && $resultat != NULL){ //unlike
			// supprimer la ligne
			$stmt = $db->prepare("DELETE FROM tab_like WHERE id_pic = :id_pic AND `login` = :user");
			$stmt->bindValue(':id_pic', $id, PDO::PARAM_INT);
			$stmt->bindValue(':user', $_SESSION['login'], PDO::PARAM_STR);
			$stmt->execute(); 
			// compter le nombre de like ligne sur l'id_pic 
			$stmt = $db->prepare("SELECT count(DISTINCT login) FROM tab_like WHERE id_pic = :id_pic");
			$stmt->bindValue(':id_pic', $id, PDO::PARAM_INT);
			$stmt->execute();
			$nblike = $stmt->fetch();
			// update la table pic a l'id pic nb_like = new_nblike
			$new_nblike = $nblike['count(DISTINCT login)'];
			$stmt = $db->prepare("UPDATE `pic` SET `nb_like`= :new_nblike WHERE id = :id_pic");
			$stmt->bindValue(':id_pic', $id, PDO::PARAM_INT);
			$stmt->bindValue(':new_nblike', $new_nblike, PDO::PARAM_INT);
			$stmt->execute();
			// exit('unlike') et relancer getlike en js
			exit($new_nblike);
		}
		else{
			// 2 si ya pas de like ajouter un ligne avec id_pic et user
			$stmt = $db->prepare("INSERT INTO tab_like(id_pic, `login`) VALUES (:id_pic, :user) ");
			$stmt->bindValue(':id_pic', $id, PDO::PARAM_INT);
			$stmt->bindValue(':user', $_SESSION['login'], PDO::PARAM_STR);
			$stmt->execute(); 
			// compter le nombre de like ligne sur l'id_pic 
			$stmt = $db->prepare("SELECT count(DISTINCT login) FROM tab_like WHERE id_pic = :id_pic");
			$stmt->bindValue(':id_pic', $id, PDO::PARAM_INT);
			$stmt->execute();
			$nblike = $stmt->fetch();
			//update la table pic a l'id pic nb_like = new_nblike
			$new_nblike = $nblike['count(DISTINCT login)'];
			$stmt = $db->prepare("UPDATE `pic` SET `nb_like`= :new_nblike WHERE id = :id_pic");
			$stmt->bindValue(':id_pic', $id, PDO::PARAM_INT);
			$stmt->bindValue(':new_nblike', $new_nblike, PDO::PARAM_INT);
			$stmt->execute();
			// exit('like') et relancer getlike en js
			exit($new_nblike);
		}
	}
}
elseif (isset($_POST['idPicCom']) && isset($_POST['comment']))
{
	if (!(isset($_SESSION['login'])))
	{
		exit("notlog");
	}
	// Je ressois id photo et commentaire j'ai session user aussi date: NOW()
	//id	date	id_pic	user	comment
	$comment = htmlentities($_POST['comment']);
	$id = $_POST['idPicCom'];
	$stmt = $db->prepare("INSERT INTO tab_comment(id_pic, `date`, user, comment) VALUES (:id_pic, NOW(), :user, :comment) ");
	$stmt->bindValue(':id_pic', $id, PDO::PARAM_INT);
	$stmt->bindValue(':user', $_SESSION['login'], PDO::PARAM_STR);
	$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
	$stmt->execute();
	// if (!$stmt->execute()){
	// 	$error = implode( ", ", $stmt->errorInfo() );
	// 	exit($error);
	// }
		//Si sendmail est == 1 j'envoie les mails
	//Je select le login de l'auteur de l'image
	$sender = $_SESSION['login'];
	$stmt = $db->prepare("SELECT user FROM pic WHERE id = :id");
	$stmt->bindValue(":id", $id, PDO::PARAM_INT);
	$stmt->execute();
	$row = $stmt->fetch();
	$row = $row['user'];
	//Je select l'adresse mail du login
	$stmt = $db->prepare("SELECT mail FROM user WHERE login = :login");
	$stmt->bindValue(":login", $row, PDO::PARAM_STR);
	$stmt->execute();
	$row = $stmt->fetch();
	$row = $row['mail'];
	//Si sendmail est == 1 j'envoie les mails
	$stmt = $db->prepare("SELECT sendmail FROM user WHERE mail = :mail");
	$stmt->bindValue(":mail", $row, PDO::PARAM_STR);
	$stmt->execute();
	$sendmail= $stmt->fetch();
	$sendmail= $sendmail['sendmail'];
	if ($sendmail == 1)
	{
		$to_email = $row;
		$subject = "Camagru - Nouveau commentaire de $sender";
		$message = "Bonjour,\n Vous avez reçu un nouveau commentaire de $sender sur votre photo :\n
		$comment";
		$headers = 'From: noreply@camagru.com';
		mail($to_email,$subject,$message,$headers);
	}
	exit($id);
}
elseif (isset($_GET['supId']))
{
	$id =$_GET['supId'];
	$stmt = $db->prepare("DELETE FROM pic WHERE user = :user AND id = :id");
	$stmt->bindValue(':id', $id, PDO::PARAM_INT);
	$stmt->bindValue(':user', $_SESSION['login'], PDO::PARAM_STR);
	if (!$stmt->execute()){
		$error = implode( ", ", $stmt->errorInfo() );
		exit($error);
	}
	$s = 0;
	$l = 15;
	$stmt = $db->prepare("SELECT id, user, path, date, nb_like, nb_comment FROM pic WHERE user = :user ORDER BY date DESC LIMIT :s, :l");
	$stmt->bindValue(':s', $s, PDO::PARAM_INT);
	$stmt->bindValue(':l', $l, PDO::PARAM_INT);
	$stmt->bindValue(':user', $_SESSION['login'], PDO::PARAM_STR);
	$stmt->execute();
	while ($data = $stmt->fetch())
	{
		echo "<div class='img_previw'>
		<div class='imgdetail1'> <img id='croix".$data['id']."' class='croix' src='../resources/img/croix.png' alt='X' onclick='sup(this)'></div>
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
	exit();
}
else
{
	echo"
	<script> 
	alert('Page non existante'); 
	window.location='../index.php';
	</script>";
	
	// header('Location: ../../index.php');
	exit();
}
?>
