<?PHP

session_start();
include("auth.php");

?>

<html>
<HEAD>
<TITlE>confirmation</TITLE>
<link rel="stylesheet" href="camagru.css">
<meta charset="UTF-8">
</HEAD>

<?PHP

include("header.html");

?>

<div id="co_main">

  <H1>Confirmation de votre inscription</H1>

</div>

<?PHP

	include("footer.html");

?>

<?PHP

include "config/database.php";

try
{
    $dsnname = $dsn.";dbname=".$name;
    $pdo = new PDO($dsnname , $user, $password);
}
catch(PDOException $e)
{
    die("DB ERROR: ". $e->getMessage());
}

$login = htmlentities($_GET['log']);

$sql = "SELECT login FROM users";
foreach ($pdo->query($sql) as $row)
{
	$lgn = substr(hash("whirlpool", $row['login']), 0, 10);
	if ($lgn === $login)
		$ret = $row['login'];
}

if (authlog($ret) == 1)
{
	$sth = $pdo->prepare("UPDATE users SET confirmed = '1' WHERE login = ?");
	$sth->bindParam(1, $ret, PDO::PARAM_STR);
	$sth->execute();

	echo '<script>alert("Your account had been created!");';
	echo 'location.href = "index.php";</script>';
}
else
{
    echo '<script>alert("Wrong link!");';
	echo 'location.href = "index.php";</script>';
}
?>