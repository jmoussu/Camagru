<?php
include '../../controller/db_root_login.php';
include '../../controller/user.php';
session_start();

if (isset($_GET['start']) && isset($_GET['limit'])) //CAM SCROLL INFINI LOAD IMAGE SCROLLUSER.JS
{
	$s = (int)$_GET['start'];
	$l = (int)$_GET['limit'];
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
