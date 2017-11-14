<?PHP

session_start();
include("auth.php");

?>

<html>
<HEAD>
<TITlE>Galery</TITLE>
<link rel="stylesheet" href="camagru.css">
<meta charset="UTF-8">
</HEAD>

<?PHP

	include("header.html");
include "ft_database.php";
include "config/database.php";

date_default_timezone_set("EUROPE/PARIS");

try
{
    $dsnname = $dsn.";dbname=".$name;
    $pdo = new PDO($dsnname , $user, $password);
}
catch(PDOException $e)
{
    die("DB ERROR: ". $e->getMessage());
}

$tmp = ['name' => NULL, 'auteur' => NULL, 'comment' => NULL];

$login = htmlentities($_SESSION['loggued_on_user']);

if (authlog($login) == 1)
{

echo '<h1>Picture</h1>';
echo '<div id="section">';

if ($_POST['photo'])
{
	$name = htmlentities($_POST['photo']);
}

if ($_GET['name'] != NULL)
{
	$name = htmlentities($_GET['name']);
}

$sbmt = htmlentities($_POST['submit']);

if ($sbmt == "Ajouter!")
{
	$name = htmlentities($_POST['name']);
	$tmp['name'] = htmlentities($_POST['name']);
	$tmp['auteur'] = htmlentities($_SESSION['loggued_on_user']);
	$tmp['comment'] = htmlentities($_POST['comment']);
	ft_send_mail_to($pdo, $tmp);
	ft_add_comments($tmp, $pdo);
}
if ($_POST['like'] != NULL)
{
	$name = htmlentities($_POST['like']);
	ft_like($pdo, $name, htmlentities($_SESSION['loggued_on_user']));
}

$tmp = ft_com_pic($pdo, $name);
$src = "images/".$name;
$like = 'ressources/Like.png';
$dlike = 'ressources/Dislike.jpg';

echo '<div id="cip"><img src="'.$src.'" width="90%" onclick="ft_picture(\''.$src.'\');">';
echo '<br /><h2>Picture from '.$tmp["auteur"].'</h2>';
echo '<h3>"'.$tmp["comment"].'"</h3><br />';
echo '<form method="POST" action="picture.php"><button id="like" type="submit" name="like" value="'.$name.'">';
$nbr = ft_nbr_like($pdo, $name);
if (ft_like_usr($pdo, $name, htmlentities($_SESSION['loggued_on_user'])) == 1)
	echo '<img src="'.$like.'" width="100%">';
else
	echo '<img src="'.$dlike.'" width="100%">';
echo '<div id="count">'.$nbr.'</div>';
echo '</button></form></div>';
?>

<div id="commentaires">
<div id="just">

<?PHP
	ft_comments($pdo, $name);
	echo '</div><form method="POST" action="picture.php">';
	echo '<input type="text" name="name" value="'.$name.'" hidden="hidden"/>';
?>

<br /><br />
<span class="pa"> Ajouter un commentaire: </span><br/>
<textarea id="t_area" name="comment" rows="2" cols="50"></textarea>
<br /><br />
<input type="submit" name="submit" value="Ajouter!" />
</form>

</div>
</div>

<?PHP

if ($_POST['p'] >= 0)
{
	$link = 'http://localhost:8080/PROJET_9_CAMAGRU/images/'.$name;
	echo '<div id="share"><a type="button" name="fb_share" share_url="'.$link.'"></a>';
	$page = htmlentities($_POST['p']);
	echo '<br /><form method="POST" action="galery.php">';
	echo '<input type="text" id="page" name="page" value="'.$page.'" hidden="hidden"/>';
	echo '<input type="submit" name="submit" value="previous"/></form></div>';
}

}
else
{
	echo '<br />You must login!';
	echo '<br /><br /><a href="galery.php" title="Galery">Galery</a>';
	echo '<a href="connexion.php">Connexion</a>';
}

include("footer.html");

?>

<div id="select" hidden="hidden">
<img src="ressources/Close.png" id="cross" width="25px" onclick="ft_close();">
<img src="" id="sel" width="90%">
</div>
<script src="picture_script.js"></script>
<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>