<?php
include '../token.php';
include '../db_root_login.php';

if (!isset($_POST['login']))
{
    exit();
}
$login = $_POST['login'];
$e = 0;
$stmt = $db->prepare("SELECT token FROM user WHERE login = :login");
$stmt->bindValue(':login', $login, PDO::PARAM_STR);
$res = $stmt->execute();
$row = $stmt->fetch();
if (!empty($row['token']))
{
    echo "Fatal error";
    $e = 1;
}
$token = token();
$row = '';
$stmt = $db->prepare("SELECT login FROM user WHERE login = :login");
$stmt->bindValue(':login', $login, PDO::PARAM_STR);
$res = $stmt->execute();
$row = $stmt->fetch();
if (empty($row))
{
    echo "Ce compte n'existe pas";
    $e = 1;
}
if ($e == 0)
{
    $stmt = $db->prepare("UPDATE user SET token = :token WHERE login = :login");
    $stmt->bindValue(':login', $login, PDO::PARAM_STR);
    $stmt->bindValue(':token', hash('whirlpool', $token), PDO::PARAM_STR);
    $stmt->execute();
    $path = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '/', 1)); // '/Camagru_OurGit'
    $stmt = $db->prepare("SELECT mail FROM user WHERE login = :login");
    $stmt->bindValue(':login', $login, PDO::PARAM_STR);
    $res = $stmt->execute();
    $row = $stmt->fetch();
    $to_email = $row['mail'];
    $subject = 'Camagru - Demande de réinitialisation du mot de passe';
    $message = "Bonjour,\n Pour réinitialiser votre mot de passe cliquez sur ce lien : \n
    http://".$_SERVER['HTTP_HOST'].$path."/view/reset.php?reset=".$token." \n
    Si vous n'avez pas demandé de réinitialisation de mot de passe, vous pouvez ignorer ce message.\n
    Merci de votre confiance.\n\n
    L'équipe Camagru";
    $headers = 'From: noreply@camagru.com';
    mail($to_email,$subject,$message,$headers);
    include '../../view/mail_sent_pwd.php';
}
