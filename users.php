<?PHP

session_start();
include("auth.php");

?>

<html>
<HEAD>
<TITlE>users</TITLE>
<link rel="stylesheet" href="camagru.css">
<meta charset="UTF-8">
</HEAD>

<?PHP

include("header.html");
include "ft_database.php";
include "config/database.php";

date_default_timezone_set("EUROPE/PARIS");

$file = NULL;

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
$sbmt = htmlentities($_POST['submit']);
$usr = htmlentities($_SESSION['loggued_on_user']);

if ($_FILES['pic']['name'] != NULL)
	$pic_name = htmlentities($_FILES['pic']['name']);

if ($sbmt == "Publier!")
{
	$tmp['name'] = htmlentities($_POST['name']);
	$tmp['comment'] = htmlentities($_POST['comment']);
	ft_save_picture($tmp, $pdo);
	$file = "images/".$tmp['name'];
}
if ($_POST['start'] != NULL || $pic_name != NULL)
{
	$good = 1;
    $time = date("_Y_z_H_i_s");
	$tmp['comment'] = "(none)";
    $tmp['auteur'] = $usr;
    $tmp['name'] = $tmp['auteur'].$time.".png";
	$file = "images/".$tmp['name'];
	if ($_FILES['pic']['name'] != NULL)
	{
		$good = 0;
		if ($_FILES['pic']['size'] > 1048576)
			echo '<script>alert("File is too big!");</script>';
		else if (substr(strrchr($pic_name,'.'),1) !== "png")
			echo '<script>alert("take only .png");</script>';
		else
		{
			$good = 1;
			move_uploaded_file($pic_name, $file);
		}
	}
	else
	{
		$current = htmlentities($_POST['start']);
		$tab = explode(",", $current, 2);
		$current = base64_decode ($tab[1]);
		file_put_contents($file, $current);
	}
	if ($good === 1)
	{
		ft_new_picture($tmp, $pdo);
		$filter = htmlentities($_POST['filter']);
		ft_merge_pic($filter, $file);
	}
}

if (authlog($usr))
{
?>

<div id="section">

<div id="filter">

<H3>Filtres</H3>

<form name="form_filter" onclick="ft_filter();">
Veuillez choisir un filtre :<br /><br /><br />
<input type="radio" name="filter" value="filters/Cigarette.png" id="fi" /><label for="Cigarette" checked="checked">Cigarette</label><br />
<img src="filters/Cigarette.png" id="photo" width="60%"><br /><br />
<input type="radio" name="filter" value="filters/Lightsaber.png" id="fi" /><label for="Sabre Laser">Sabre Laser</label><br />
<img src="filters/Lightsaber.png" id="photo" width="60%"><br /><br />
<input type="radio" name="filter" value="filters/Love.png" id="fi" /><label for="Cadre love">Cadre love</label><br />
<img src="filters/Love.png" id="photo" width="60%"><br /><br />
<input type="radio" name="filter" value="filters/Glasses.png" id="fi" /><label for="Lunette du thug">Lunette du thug</label>
<img src="filters/Glasses.png" id="photo" width="80%"><br />
</form>

</div>

<div id="main">
<H1>Take a picture!</H1>
<br />

<form enctype="multipart/form-data">

<div id="form_cam">
<video id="video"></video>
</div>
<br />

<canvas id="canvas" hidden="hidden"></canvas>
<br />

<input type="text" id="pic_filter" name="filter" value="" hidden="hidden"/>
<button name="start" id="start" formmethod="POST" formaction="users.php" hidden="hidden">Prendre une photo</button>
<input type="file" name="pic" id="upload_img" hidden="hidden"/>

<br /><br />

<?PHP
	 if ($good === 1)
	 {
		 echo '<img src="'.$file.'" id="photo">';
	 }
?>

<br />

<script src="webmedia.js"></script>

</form>

<?PHP

		if ($_POST['start'] != NULL && $good === 1)
 		{
			echo '<H5>Cette photo vous plait?<br />Ajoutez un commentaire et publiez-la!</H5>
				<form method="POST" action="users.php">
				<input type="text" name="name" value="'.$tmp["name"].'" hidden="hidden"/>
				<span class="pa">Commentaire: </span><br/>
				<textarea id="t_area" name="comment" rows="2" cols="50"></textarea>
				<br /><br />
				<input type="submit" name="submit" onclick="ft_share();" value="Publier!" /></form>';
		}

		echo '</div><div id="side"><H3>Old Pictures!</H3><div id="prtpht">';
		ft_auth_pic($usr, $pdo);
		echo '</div></div></div>';

}
else
	echo "<div id='section'><span id='pa'>You must log in!</span></div>";
include("footer.html");
?>