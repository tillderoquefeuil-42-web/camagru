#!/usr/bin/php
<?PHP

include "database.php";

//Database

try 
{
    $pdo = new PDO($dsn, $user, $password);
	$pdo->exec("DROP DATABASE IF EXISTS ".$name);
	$data = $pdo->exec("CREATE DATABASE IF NOT EXISTS ".$name);
	if ($data)
		echo "Database : ".$name." created".PHP_EOL;
	else
		print_r($pdo->errorInfo());
	$pdo = NULL;
}
catch (PDOException $e)
{
    echo 'Connection failed: ' . $e->getMessage();
}

try
{
	$dsnname = $dsn.";dbname=".$name;
	$pdo = new PDO($dsnname , $user, $password);
}
catch(PDOException $e)
{
	die("DB ERROR: ". $e->getMessage());
}

//Tables

//usr tbl
$querry = ("DROP DATABASE IF EXISTS ".$tables['users']);
$pdo->exec($querry);
$querry = "CREATE TABLE ".$tables['users']."(id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL, login varchar(255) UNIQUE NOT NULL, email varchar(255) NOT NULL UNIQUE, mdp varchar(255) NOT NULL, confirmed BOOLEAN NOT NULL DEFAULT 0);";
$pdo->exec($querry);
echo "Table : ".$tables['users']." created".PHP_EOL;

//pctr tbl
$querry = ("DROP DATABASE IF EXISTS ".$tables['pictures']);
$pdo->exec($querry);
$querry = "CREATE TABLE ".$tables['pictures']."(id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL, name varchar(255) UNIQUE NOT NULL, date DATETIME NOT NULL DEFAULT NOW(), auteur VARCHAR(255) NOT NULL, comment TEXT NOT NULL, validate BOOLEAN NOT NULL DEFAULT 0);";
$pdo->exec($querry);
echo "Table : ".$tables['pictures']." created".PHP_EOL;

//cmmt tbl
$querry = ("DROP DATABASE IF EXISTS ".$tables['comments']);
$pdo->exec($querry);
$querry = "CREATE TABLE ".$tables['comments']."(id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL, name varchar(255) NOT NULL, date DATETIME NOT NULL DEFAULT NOW(), auteur VARCHAR(255) NOT NULL, comment TEXT NOT NULL);";
$pdo->exec($querry);
echo "Table : ".$tables['comments']." created".PHP_EOL;

//lks tbl
$querry = ("DROP DATABASE IF EXISTS ".$tables['likes']);
$pdo->exec($querry);
$querry = "CREATE TABLE ".$tables['likes']."(id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL, name varchar(255) NOT NULL, auteur VARCHAR(255) NOT NULL);";
$pdo->exec($querry);
echo "Table : ".$tables['likes']." created".PHP_EOL;


if (!file_exists($foldername))
{
	echo "Folder : 'images' created".PHP_EOL;
	mkdir($foldername);
}

$pdo = null;
echo "you can run Camagru!".PHP_EOL;

?>