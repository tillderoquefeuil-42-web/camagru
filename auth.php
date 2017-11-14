<?PHP

function auth($login, $passwd)
{
	include "config/database.php";

	if ($login === NULL || $passwd === NULL)
		return (0);
	try
	{
		$dsnname = $dsn.";dbname=".$name;
		$pdo = new PDO($dsnname , $user, $password);
	}
	catch(PDOException $e)
	{
		die("DB ERROR: ". $e->getMessage());
	}
	$passwd = hash("whirlpool", hash("whirlpool", $passwd));
	$sql = "SELECT login, mdp FROM users";
	foreach ($pdo->query($sql) as $row)
	{
		if ($row["login"] === $login)
			$mdp = ($row["mdp"]);
	}
	$mdp = substr($mdp, 6, -6);
	if ($mdp === $passwd)
		return (TRUE);
	return (FALSE);
}

function authlog($login)
{
	include "config/database.php";

	if ($login === NULL)
		return (0);
	try
    {
		$dsnname = $dsn.";dbname=".$name;
        $pdo = new PDO($dsnname , $user, $password);
    }
    catch(PDOException $e)
    {
		die("DB ERROR: ". $e->getMessage());
    }
	$sql = "SELECT login FROM users";
    foreach ($pdo->query($sql) as $row)
    {
        if ($row["login"] === $login)
            return (1);
    }
    return (0);
}

function ft_usr($login)
{
	include "config/database.php";

    if ($login === NULL)
        return (0);
    try
    {
        $dsnname = $dsn.";dbname=".$name;
        $pdo = new PDO($dsnname , $user, $password);
    }
    catch(PDOException $e)
    {
        die("DB ERROR: ". $e->getMessage());
    }
    $sql = "SELECT login, confirmed FROM users";
    foreach ($pdo->query($sql) as $row)
    {
        if ($row["login"] === $login)
            return ($row["confirmed"]);
    }
    return (0);
}
?>