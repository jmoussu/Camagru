<?php

function resize($src, $newWidth) {

	list($w, $h) = getimagesize($src);

	$newHeight = ($h / $w) * $newWidth;
	$new_filter = imagecreatetruecolor($newWidth, $newHeight);

	imagecolortransparent($new_filter, imagecolorallocatealpha($new_filter, 0, 0, 0, 127));
	imagealphablending($new_filter, false);
	imagesavealpha($new_filter, true);
	
	imagecopyresampled($new_filter, imagecreatefrompng($src), 0, 0, 0, 0, $newWidth, $newHeight, $w, $h);
	return(array($new_filter, $newWidth, $newHeight));
}
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

if (isset($_POST['filter']) && isset($_POST['new_width']) && isset($_POST['t_x']) && isset($_POST['t_y']))
{
	$c = count(glob("../../resources/user/".$_SESSION['login']."/*.png"));
	$set_filter = $_POST['filter'];
	$src = imagecreatefrompng("../../resources/user/".$_SESSION['login']."/".$c.".png");
	$dst = imagecreatetruecolor(1000,750);
	$filter = imagecreatefrompng("../../resources/filter/{$set_filter}.png");
	list($new_filter, $newWidth, $newHeight) = resize("../../resources/filter/{$set_filter}.png", 	$_POST['new_width']);
	// list($width, $height) = getimagesize($new_filter);
	// cree function qui resize pour augmanter la taille et diminuer ta taille du filter ?
	if (!(file_exists("../../resources/user/".$_SESSION['login']."")))
	{
		mkdir("../../resources/user/".$_SESSION['login']."");
	}
	imagecopy($dst, $src, 0, 0, 0, 0, 1000, 750);
	if ($newHeight > 0 && $newWidth > 0)
	{
		$x = ((1000/2) - ($newWidth/2)) + $_POST['t_x'];
		$y = ((750/2) - ($newHeight/2)) + $_POST['t_y'];
	}
	else
	{
		$x = 100;
		$y = 100;
	}
	imagecopy($dst, $new_filter, $x, $y, 0, 0, $newWidth, $newHeight); // 60, 60, 0, 0, 318, 479)
	imagepng($dst, "../../resources/user/".$_SESSION['login']."/".$c.".png");
	exit('AddFilter.php ok');
}
else
	exit('erreur add_filter.php');
