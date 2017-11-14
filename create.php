<?PHP

session_start();
include("auth.php");

?>

<html>
<HEAD>
<TITlE>Nouveau compte</TITLE>
<link rel="stylesheet" href="camagru.css">
<meta charset="UTF-8">
</HEAD>

<?PHP
include("header.html");

$c_mail = NULL;
$c_login = NULL;
if ($_POST['mail'])
	$c_mail = htmlentities($_POST['mail']);
if ($_POST['login'])
    $c_login = htmlentities($_POST['login']);

echo '<div id="co_main">';
echo '<form method="POST" action="create.php"><H1>Creer un compte :</H1><br />';
echo '<span class="pa">Adresse mail: </span><textarea id="t_area" name="mail" rows="1" cols="20">'.$c_mail.'</textarea><br />';
echo '<span class="pa">Identifiant: </span><textarea id="t_area" name="login" rows="1" cols="20">'.$c_login.'</textarea><br />';
echo '<span class="pa">Mot de passe: </span><input type="password" name="passwd" placeholder="(6 cara. mini.)"/><br /><br />';
echo '<input type="submit" name="submit" value="OK" /></form></div>';

include("footer.html");

include "ft_database.php";
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

$nc = 0;
$tmp = ['login' => NULL, 'passwd' => NULL, 'mail' => NULL];
$err = NULL;

if ($_POST['login'] != NULL && $_POST['passwd'] != NULL && $_POST['mail'] != NULL && $_POST['submit'] != NULL)
{
	$lgn = htmlentities($_POST['login']);
	$pswd = htmlentities($_POST['passwd']);
	$mail = htmlentities($_POST['mail']);
	$sbmt = htmlentities($_POST['submit']);

	if (ft_log_exist($lgn, $pdo) == 0)
		$tmp['login'] = $lgn;
	else
		$err = ": login already exist!";

	if (ft_good_pass($pswd) == 1)
		$tmp['passwd'] = ft_salt(hash("whirlpool", hash("whirlpool", $pswd)));
	else
		$err = ": wrong password's format! (must contain minimum one uppercase, one lowercase, one number)";

	if (ft_mail_exist($mail, $pdo) == 0)
		$tmp['mail'] = $mail;
	else
		$err = ": email already exist!";

	if ($sbmt == "OK" && $tmp['login'] != NULL && $tmp['passwd'] != NULL && $tmp['mail'] != NULL)
	{
		ft_put_all_pass($tmp, $pdo);
		$get = "log=".substr(hash("whirlpool", $tmp['login']), 0, 10);
		$link = "http://localhost:8080/PROJET_9_CAMAGRU/confirm.php?".$get;
		$msg = 'Hi '.$tmp['login'].'! To end the creation of your account, juste click on this link : '.$link;
		mail($tmp['mail'], "camagru", $msg);
		echo '<script>alert("Thank you! We\'ll send you a mail in a few seconds, validate your account by clicking on the link in the mail!");';
		echo 'location.href = "connexion.php";</script>';
    }
	else
		echo '<script>alert("Account can\'t be created'.$err.'")</script>';
}

?>