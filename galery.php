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

$sbmt = htmlentities($_POST['submit']);

if ($sbmt == "last")
	$page = htmlentities($_POST['page']) - 1; 
else if ($sbmt == "next")
	$page = htmlentities($_POST['page']) + 1;
else if ($sbmt == "previous")
	$page = htmlentities($_POST['page']) + 1;
else
	$page = 1;

$p_max = ft_p_max($pdo);

echo '<H2>Galery</H2>';
echo '<form method="POST" action="galery.php">';
echo '<input type="text" id="fpage" name="page" value="'.$page.'" hidden="hidden"/>';
echo '<input type="text" id="fp_max" name="p_max" value="'.$p_max.'" hidden="hidden"/>';
echo '<input type="submit" name="submit" value="last" id="flast"/>';
echo '<input type="submit" name="submit" value="next" id="fnext"/></form>';
echo '<div id="galery">';

ft_galery_pic($pdo, $page);
echo '</div><form method="POST" action="galery.php">';
echo '<input type="text" id="page" name="page" value="'.$page.'" hidden="hidden"/>';
echo '<input type="text" id="p_max" name="p_max" value="'.$p_max.'" hidden="hidden"/>';

?>

<br />
<input type="submit" name="submit" value="last" id="last"/>
<input type="submit" name="submit" value="next" id="next"/>
</form>

<?PHP
include("footer.html");
?>

<script src="galery_script.js"></script>