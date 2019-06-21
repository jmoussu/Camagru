<?php
include '../../controller/db_root_login.php';
include '../../controller/user.php';

session_start();

$login = $_SESSION['login'];
if (isset($_POST['submit']) && $_POST['submit'] == 'OK')
{
    if ($_POST['choice'] == "oui")
    {
        $stmt = $db->prepare("UPDATE user SET sendmail = 1 WHERE login = :login ");
		$stmt->bindValue(':login', $login, PDO::PARAM_STR);
        $stmt->execute();
        echo "<p>Changement effectué";
    }
    if ($_POST['choice'] == "non")
    {
        $stmt = $db->prepare("UPDATE user SET sendmail = 0 WHERE login = :login ");
		$stmt->bindValue(':login', $login, PDO::PARAM_STR);
        $stmt->execute();
        echo "<p>Changement effectué";
    }
}
