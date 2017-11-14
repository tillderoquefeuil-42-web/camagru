<?PHP

session_start();
include_once("auth.php");

?>

<html>
<HEAD>
<TITlE>Contact</TITLE>
<link rel="stylesheet" href="camagru.css">
<meta charset="UTF-8">
</HEAD>

<?PHP
	
include_once("header.html");
include_once("ft_database.php");
include_once("config/database.php");

date_default_timezone_set("EUROPE/PARIS");

$mail = NULL;
$com = NULL;
$err = 0;

$sbmt = htmlentities($_POST['submit']);

if ($sbmt == 'envoyer!')
{

	$err_msg = "Email sent!";
    if ($_POST['mail'] != NULL)
		$mail = htmlentities($_POST['mail']);
	if ($_POST['comment'] != NULL)
        $com = htmlentities($_POST['comment']);

	if ($mail == NULL)
	{
		$err = 2;
		$err_msg = "Must be a email!";
	}
	else if ($com == NULL)
	{
		$err = 1;
		$err_msg = "Must be a comment!";
	}
	else if (ft_valid_mail($mail) == 0)
	{
		$err = 3;
		$err_msg = "Invalid email adress!";
	}

	echo '<script>alert("'.$err_msg.'");</script>';
	if ($err == 0)
	{
		$add = "tde-roqu@student.42.fr";
		$msg = $mail.' sent you a request :'.$com;
		mail($add, "Contact", $msg);
		$mail = NULL;
		$com = NULL;
	}
}
	
?>

<h1>Nous contacter</h1>
<div id="main"><form method="POST" action="contact.php">
<span class="pa"> Votre adresse mail: </span><br/>

<?PHP

echo '<textarea id="t_area" name="mail" rows="1" cols="50">'.$mail.'</textarea><br/><br/>';
echo '<span class="pa"> Votre message: </span><br/>';
echo '<textarea id="t_area" name="comment" rows="4" cols="50">'.$com.'</textarea>';

?>

<br /><br />
<input type="submit" name="submit" value="envoyer!" />
</form></div>

<?PHP

include("footer.html");

?>