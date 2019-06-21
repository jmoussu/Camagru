<?php
function install()
{
	$path = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '/', 1)); // '/Camagru_OurGit'
	include $_SERVER['DOCUMENT_ROOT'].$path.'/config/database.php';
	try
	{
		$db = new PDO($DB_DSN, $DB_USER , $DB_PASSWORD);
	}
	catch (Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}
	$file = file_get_contents("camagru.sql");
	$file = explode(';', $file);
	$db->query('DROP DATABASE camagru');
	$db->query('CREATE DATABASE camagru');
	$db->query('USE camagru');
	foreach($file as $tmp)
		$db->query($tmp);
	file_put_contents("installed", "ok");
	
}
if (isset($_GET["done"]))
{
	echo "<script>alert('La base de donnee a bien ete intialisee'); window.location = '../index.php';</script>";
}

if (isset($_POST['passwd']))
{
	$passwd = hash('whirlpool', $_POST['passwd']);
	if ($passwd == "50c467eed9f98a69aa865493113981e7b12faddfabf1139607a3ffb24fb86c6658e6ec7637865d9568d1408ebeddcfb4b31c18facac4cef4385cba6a859df946")
	{
		if (file_exists("installed"))
		{
			echo "
			<h2>La base de donnee est deja initialisee. Voulez vous forcer la reinitialisation ?</h2>
			<form method='POST' action = >
			<input type='submit' name='force' value='force' href='setup.php'/>
			<br />
			";
		}
		else
		{
			install();
			header('Location: setup.php?done'); 
		}
	}
	else
	{
		echo "<script>alert('Vous n avez pas la permission d executer cette action'); window.location = '../index.php';</script>";
	}
}
if (isset($_POST['force']))
	{
		install();
		header('Location: setup.php?done'); 	
	}

?>

<html>
<head>
	<meta charset="utf-8" />
	<title>Install</title>
</head>
<body>
<?php
if (!isset($_POST['passwd']))
echo"
<h2>Veuillez entrer le mot de passe root</h2>
<form method='POST' action = >
		<input type='password' name='passwd' value='' placeholder='Mot de Passe'/>
		<br />
		<input type='submit' name='submit' value='OK' href='setup.php'/>
			<br />
</form>";
?>
</body>
