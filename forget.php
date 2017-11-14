<?PHP

session_start();
include("auth.php");

?>

<html>
<HEAD>
<TITlE>Mot de passe oublie</TITLE>
<link rel="stylesheet" href="camagru.css">
<meta charset="UTF-8">
</HEAD>

<?PHP
include("header.html");
?>

<div id="co_main">

<form method="POST" action="forget.php">
  <H1>Entrez votre identifiant ou votre adresse mail :</H1>
  <br />
    <span class="pa">Identifiant: </span><input type="text" name="login"/>
  <br />
    <span class="pa">Adresse mail: </span><input type="text" name="mail"/>
  <br /><br />
  <input type="submit" name="submit" value="OK" />
</form>

</div>

<?PHP
	include("footer.html");
?>

<?PHP

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
$cmp = 0;
$tmp = array('login' => NULL, 'passwd' => NULL, 'mail' => NULL);
$pswd = NULL;

if (($_POST['login'] != NULL || $_POST['mail'] != NULL) && $_POST['submit'] != NULL)
{
	$lgn = htmlentities($_POST['login']);
	$mail = htmlentities($_POST['mail']);
    $sbmt = htmlentities($_POST['submit']);

	if (ft_log_exist($lgn, $pdo) != NULL)
	{
		$tmp['login'] = $lgn;
        $mdp = ft_salt($lgn);
		$mail = ft_mailing($lgn, $pdo);
	}
	else if (ft_mail_exist($mail, $pdo) == 1)
	{
        $mdp = ft_salt($mail);
        $tmp['mail'] = $mail;
		$lgn = ft_r_mailing($mail, $pdo);
	}

	if ($sbmt == "OK" && $mdp != NULL)
    {
		$pswd = mb_substr(hash("whirlpool", $mdp), 0, 8);
        $tmp['passwd'] = ft_salt(hash("whirlpool", hash("whirlpool", $pswd)));
		ft_modif_pass($tmp, $pdo);
		$link = "http://localhost:8080/PROJET_9_CAMAGRU/modif.php!";
		$msg = 'Hi '.$lgn.'! Your new password is '.$pswd.', click on this link to modify it: '.$link;
		mail($mail, "New Password", $msg);
		echo '<script>alert("We\'ll send you a mail with your new password");';
		echo 'location.href = "connexion.php";</script>';
	}
	else
		echo '<script>alert("Wrong login or mail")</script>';
}

?>