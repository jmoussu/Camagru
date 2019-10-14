<?php
include '../../controller/db_root_login.php';
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

$postdata = file_get_contents("php://input");
if (isset($postdata))
{
	$data = $postdata;
	if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
		$data = substr($data, strpos($data, ',') + 1);
		$data = base64_decode($data);
		if ($data === false) {
			throw new \Exception('base64_decode failed');
		}
	}
	// else {
	// 	throw new \Exception('did not match data URI with image data');
	// }

	if (!(file_exists("../../resources/user/".$_SESSION['login']."")))
	{
		mkdir("../../resources/user/".$_SESSION['login']."");
	}
	$c = count(glob("../../resources/user/".$_SESSION['login']."/*.png"));
	$r = $c + 1;
	$path = "../resources/user/".$_SESSION['login']."/".$r.".png";
	$img = imagecreatefromstring($data);
	imageflip($img, IMG_FLIP_HORIZONTAL);
	imagepng($img, "../../resources/user/".$_SESSION['login']."/".$r.".png");
	$stmt = $db->prepare("INSERT INTO pic (user, path, date) VALUES (:user, :path, NOW())");
	$stmt->bindValue(':user', $_SESSION['login'], PDO::PARAM_STR);
	$stmt->bindValue(':path', $path, PDO::PARAM_STR);
	$stmt->execute();
	// exit();
}
