<?PHP

session_start();
include("auth.php");

?>

<html>
<HEAD>
<TITlE>My Pictures</TITLE>
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

$usr = htmlentities($_SESSION['loggued_on_user']);
$conf = htmlentities($_POST['conf']);
$photo = htmlentities($_POST['photo']);

if ($photo != NULL && $conf == 'true')
{
	ft_delete_pic($pdo, $photo);
}

$sbmt = htmlentities($_POST['submit']);

if ($sbmt == "last")
    $page = htmlentities($_POST['page']) - 1;
else if ($sbmt == "next")
    $page = htmlentities($_POST['page']) + 1;
else
	$page = 1;

$p_max = ft_my_p_max($pdo, $usr);

echo '<div id="mypic"><H2>My Pictures</H2><form method="POST" action="profile.php">';
echo '<input type="text" id="conf" name="conf" value="" hidden="hidden"/>';
echo '<div id="galery">';
ft_my_pics($pdo, $page, $usr);
echo '</div></form><form method="POST" action="profile.php">';
echo '<input type="text" id="page" name="page" value="'.$page.'" hidden="hidden"/>';
echo '<input type="text" id="p_max" name="p_max" value="'.$p_max.'" hidden="hidden"/>';

?>

<br />
<input type="submit" name="submit" value="last" id="last"/>
<input type="submit" name="submit" value="next" id="next"/>
</form>
</div>
<?PHP
include("footer.html");
?>

<script src="profile_script.js"></script>