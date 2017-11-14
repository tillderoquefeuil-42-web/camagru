<?PHP

session_start();
include("auth.php");

?>

<html>
<HEAD>
<TITlE>Connexion</TITLE>
<link rel="stylesheet" href="camagru.css">
<meta charset="UTF-8">
</HEAD>

<?PHP

include("header.html");

?>

<div id="co_main">

<form method="POST" action="connexion.php">
  <H1>Connexion</H1>
  <br />
  <span class="pa">Identifiant: </span><input type="text" name="login"/>
  <br />
  <span class="pa">Mot de passe: </span><input type="password" name="passwd"/>
 <br /><br />
  <input type="submit" name="submit" value="OK" />
</form>
<a class="pa" href="create.php" title="New Account">Creer un compte</a>
<a class="pa" href="forget.php" title="forget Password">Mot de Passe oublie</a>

</div>

<?PHP

include("footer.html");

?>

<?PHP

$ssn = htmlentities($_SESSION['loggued_on_user']);
$lgn = htmlentities($_POST['login']);
$pswd = htmlentities($_POST['passwd']);
$sbmt = htmlentities($_POST["submit"]);

if (authlog($ssn) && $sbmt == "OK")
	echo '<script>alert("Must logout before login!")</script>';
else if (auth($lgn, $pswd) && $sbmt == "OK" && ft_usr($lgn) == 1)
{
	$_SESSION['loggued_on_user'] = $lgn;
	echo '<script>alert("You\'ve been successfully logged!");';
    echo 'location.href = "index.php";</script>';
}
else if (auth($lgn, $pswd) && $sbmt == "OK" && ft_usr($lgn) != 1)
	echo '<script>alert("Your account had not been validated!")</script>';
else if ($sbmt == "OK")
{
	$_SESSION['loggued_on_user'] = "";
	echo '<script>alert("Wrong login or password!")</script>';
}
?>