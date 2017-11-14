<?PHP

session_start();
include("auth.php");

?>

<html>
<HEAD>
<TITlE>Modifier mot de passe</TITLE>
<link rel="stylesheet" href="camagru.css">
<meta charset="UTF-8">
</HEAD>

<?PHP
include("header.html");
?>

<div id="co_main">

<form method="POST" action="modif.php">
  <H1>Changer de mot de passe :</H1>
  <br />
	<span class="pa">Adresse email: </span><input type="text" name="mail"/>
  <br />
	<span class="pa">Ancien Mot de passe: </span><input type="password" name="oldpw"/>
  <br />
	<span class="pa">Nouveau Mot de passe: </span><input type="password" name="newpw"/>
  <br />
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

if ($_POST['mail'] != NULL && $_POST['oldpw'] != NULL && $_POST['newpw'] != NULL && $_POST['submit'] != NULL)
{

	$mail = htmlentities($_POST['mail']);
	$oldpw = htmlentities($_POST['oldpw']);
	$newpw = htmlentities($_POST['newpw']);
	$sbmt = htmlentities($_POST['submit']);
	$err = NULL;

	if (ft_mail_exist($mail, $pdo) == 1)
    {
		$lgn = ft_r_mailing($mail, $pdo);
		$oldpswd = ft_old_pass(NULL, $lgn, $pdo);
		$oldpswd = substr($oldpswd, 6, -6);
		$tmp['login'] = $lgn;
		$oldtest = hash("whirlpool", hash("whirlpool", $oldpw));
		if ($oldtest == $oldpswd)
		{
			if (ft_good_pass($newpw, $pdo) == 1)
			{
				$tmp['passwd'] = ft_salt(hash("whirlpool", hash("whirlpool", $newpw)));
			}
			else
			{
				$err = ": wrong password's format! (must contain minimum one uppercase, one lowercase, one number)";
			}
		}
		else
			$err = ": wrong old password!";
	}
    if ($sbmt == "OK" && $tmp['login'] != NULL && $tmp['passwd'] != NULL && $err == NULL)
	{
        ft_modif_pass($tmp, $pdo);
		echo '<script>alert("Password successfuly modified!");';
        echo 'location.href = "connexion.php";</script>';
    }
	else
	{
		echo '<script>alert("Password can\'t be modified'.$err.'")</script>';
	}
}

?>