<?PHP

function ft_log_exist($lgn, $pdo)
{
	$sql = "SELECT login FROM users";
	foreach ($pdo->query($sql) as $row)
	{
		if ($row["login"] === $lgn)
			return (1);
	}
	return (0);
}

function ft_mail_exist($mail, $pdo)
{
	$sql = "SELECT email FROM users";
    foreach ($pdo->query($sql) as $row)
    {
		if ($row["email"] === $mail)
			return (1);
    }
    return (0);
}

function ft_old_pass($mail, $lgn, $pdo)
{
	if ($mail == NULL)
	{
		$sql = "SELECT login, mdp FROM users";
		foreach ($pdo->query($sql) as $row)
		{
			if ($row["login"] === $lgn)
				return ($row["mdp"]);
		}
	}
	else if ($lgn == NULL)
	{
		$sql = "SELECT mail, mdp FROM users";
		foreach ($pdo->query($sql) as $row)
		{
			if ($row["mail"] === $lgn)
				return ($row["mdp"]);
		}
	}
    return (NULL);
}

function ft_r_mailing($mail, $pdo)
{
    $sql = "SELECT login, email FROM users";
    foreach ($pdo->query($sql) as $row)
    {
        if ($row["email"] === $mail)
            return ($row["login"]);
    }
    return (NULL);
}

function ft_mailing($lgn, $pdo)
{
    $sql = "SELECT login, email FROM users";
    foreach ($pdo->query($sql) as $row)
    {
        if ($row["login"] === $lgn)
            return ($row["email"]);
    }
    return (NULL);
}

function ft_put_all_pass($tmp, $pdo)
{
	$sth = $pdo->prepare("INSERT INTO users (login, email, mdp) VALUE (?, ?, ?)");
	$sth->bindParam(1, $tmp['login'], PDO::PARAM_STR);
	$sth->bindParam(2, $tmp['mail'], PDO::PARAM_STR);
	$sth->bindParam(3, $tmp['passwd'], PDO::PARAM_STR);
	$sth->execute();
}

function ft_modif_pass($tmp, $pdo)
{
	if ($tmp['login'] != NULL)
	{
		$sth = $pdo->prepare("UPDATE users SET mdp = ? WHERE login = ?");
		$sth->bindParam(1, $tmp['passwd'], PDO::PARAM_STR);
		$sth->bindParam(2, $tmp['login'], PDO::PARAM_STR);
	}
	else if ($tmp['mail'] != NULL)
	{
		$sth = $pdo->prepare("UPDATE users SET mdp = ? WHERE email = ?");
		$sth->bindParam(1, $tmp['passwd'], PDO::PARAM_STR);
        $sth->bindParam(2, $tmp['mail'], PDO::PARAM_STR);
	}
	$sth->execute();
}

function ft_good_pass($mdp)
{
	$sec = 0;
	if (strlen($mdp) >= 6)
		$sec = $sec + 1;
	$sec = $sec + preg_match("/[0-9]/", $mdp);
	$sec = $sec + preg_match("/[a-z]/", $mdp);
	$sec = $sec + preg_match("/[A-Z]/", $mdp);
	if ($sec == 4)
		return (1);
	else
		return (0);
}

function ft_salt($mdp)
{
	$str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$j = 0;
	while ($j < 6)
	{
		$i = rand (0, 61);
		$presalt = $presalt.$str[$i];
		$j++;
	}
	$j = 0;
    while ($j < 6)
    {
		$i = rand (0, 61);
        $susalt = $susalt.$str[$i];
		$j++;
    }
	$mdp = $presalt.$mdp.$susalt;
	return ($mdp);
}

function ft_new_picture($tmp, $pdo)
{
    $sth = $pdo->prepare("INSERT INTO pictures (name, auteur, comment) VALUE (?, ?, ?)");
    $sth->bindParam(1, $tmp['name'], PDO::PARAM_STR);
    $sth->bindParam(2, $tmp['auteur'], PDO::PARAM_STR);
	$sth->bindParam(3, $tmp['comment'], PDO::PARAM_STR);
    $sth->execute();
}

function ft_save_picture($tmp, $pdo)
{
    $sth = $pdo->prepare("UPDATE pictures SET comment = ? WHERE name = ?");
    $sth->bindParam(1, $tmp['comment'], PDO::PARAM_STR);
    $sth->bindParam(2, $tmp['name'], PDO::PARAM_STR);
    $sth->execute();
	$sth = $pdo->prepare("UPDATE pictures SET validate = '1' WHERE name = ?");
	$sth->bindParam(1, $tmp['name'], PDO::PARAM_STR);
	$sth->execute();
}

function ft_auth_pic($lgn, $pdo)
{
    $sql = "SELECT name, auteur FROM pictures WHERE validate = '1' ORDER BY name DESC;";
    foreach ($pdo->query($sql) as $row)
    {
        if ($row["auteur"] === $lgn)
		{
			$file = "images/".$row["name"];
			echo '<div id="pic"><img src="'.$file.'" id="photo" width="80%"></div>';
		}
	}
}

function ft_galery_pic($pdo, $page)
{
    $sql = "SELECT name, auteur, comment FROM pictures WHERE validate = '1' ORDER BY date DESC;";
	$nbr = 0;
	$page = $page - 1;
	$lim = $page * 6;
    foreach ($pdo->query($sql) as $row)
    {
		$file = "images/".$row["name"];
		$name = $row["name"];
		$nbr++;
		if ($nbr > $lim && $nbr <= $lim + 6)
		{
			echo '<div id="gal"><form method="POST" action="picture.php">';
			echo '<input type="text" id="p" name="p" value="'.$page.'" hidden="hidden"/>';
			echo '<button class="" type="submit" name="photo" value="'.$name.'">';
			echo '<img src="'.$file.'" class="photo" width="95%"></button>';
			echo '<span class="comm">'.$row["comment"].'</span><br /></form></div>';
		}
	}
}

function ft_p_max($pdo)
{
	$sql = "SELECT name FROM pictures WHERE validate = '1' ORDER BY name DESC;";
    $nbr = 0;
    foreach ($pdo->query($sql) as $row)
	{
        $nbr++;
	}
	$nbr = $nbr / 6;
	$nbr = ceil($nbr);
	return ($nbr);
}

function ft_merge_pic($filter, $img)
{
	$dest = imagecreatefrompng($img);
	$src = imagecreatefrompng($filter);
	
	$l_src = imagesx($src);
	$h_src = imagesy($src);
	$l_dest = imagesx($dest);
	$h_dest = imagesy($dest);
	
	$dest_x = $l_dest - $l_src;
	$dest_y = $h_dest - $h_src;

	if ($filter === "filters/Cigarette.png")
    {
        $dest_x = $dest_x / 2;
        $dest_y = $dest_y / 2 - 57;
    }
	
	imagecopy($dest, $src, $dest_x, $dest_y, 0, 0, $l_src, $h_src);
	imagepng($dest, $img);
}

function ft_com_pic($pdo, $name)
{
    $sql = "SELECT name, auteur, comment FROM pictures WHERE validate = '1' ORDER BY name DESC;";
    foreach ($pdo->query($sql) as $row)
    {
        if ($name === $row['name'])
        {
			return ($row);
		}
    }
}

function ft_comments($pdo, $name)
{
    $sql = "SELECT name, auteur, comment, date FROM comments ORDER BY date DESC;";
	echo '<div><span>Commentaires</span><br /><br />';
    foreach ($pdo->query($sql) as $row)
    {
		if ($row["name"] == $name)
		{
			echo '<div class="comments"><div class="profile">';
			echo '<span class="auth">'.$row["auteur"].'</span><br /><span class="date">'.$row["date"].'</span></div>';
			echo '<div class="com">'.$row["comment"].'</div></div>';
		}
	}
	echo '</div>';
}

function ft_add_comments($tmp, $pdo)
{
    $sth = $pdo->prepare("INSERT INTO comments (name, auteur, comment) VALUE (?, ?, ?);");
    $sth->bindParam(1, $tmp['name'], PDO::PARAM_STR);
    $sth->bindParam(2, $tmp['auteur'], PDO::PARAM_STR);
	$sth->bindParam(3, $tmp['comment'], PDO::PARAM_STR);
    $sth->execute();
}

function ft_nbr_like($pdo, $name)
{
	$likes = 0;
    $sql = "SELECT name, auteur FROM likes;";
    foreach ($pdo->query($sql) as $row)
    {
        if ($row["name"] == $name)
			$likes++;
    }
    return ($likes);
}

function ft_like_usr($pdo, $name, $usr)
{
    $like = 0;
    $sql = "SELECT name, auteur FROM likes;";
    foreach ($pdo->query($sql) as $row)
    {
        if ($row["name"] == $name && $row["auteur"] == $usr)
            $like++;
    }
	return ($like);
}

function ft_like($pdo, $name, $usr)
{
	$like = 0;
	$sql = "SELECT name, auteur FROM likes;";
	foreach ($pdo->query($sql) as $row)
	{
		if ($row["name"] == $name && $row["auteur"] == $usr)
			$like++;
	}
	if ($like == 0)
		$sth = $pdo->prepare("INSERT INTO likes (name, auteur) VALUE (?, ?);");
	else
		$sth = $pdo->prepare("DELETE FROM likes WHERE name = ? AND auteur = ?");
	$sth->bindParam(1, $name, PDO::PARAM_STR);
	$sth->bindParam(2, $usr, PDO::PARAM_STR);
	$sth->execute();
}

function ft_my_pics($pdo, $page, $usr)
{
    $sql = "SELECT name, auteur, comment FROM pictures WHERE validate = '1' ORDER BY name DESC;";
    $nbr = 0;
	$page = $page - 1;
    $lim = $page * 12;
    foreach ($pdo->query($sql) as $row)
    {
		$file = "images/".$row["name"];
		$name = $row["name"];
		if ($row["auteur"] == $usr)
		{
			$nbr++;
			if ($nbr > $lim && $nbr <= $lim + 12)
			{
				echo '<div id="gal">';
				echo '<button class="" type="submit" onclick="ft_del();" name="photo" value="'.$name.'">';
				echo '<img src="'.$file.'" class="photo" width="95%"></button>';
				echo '<span class="comm">'.$row["comment"].'</span><br /></div>';
			}
		}
    }
}

function ft_my_p_max($pdo, $usr)
{
    $sql = "SELECT name, auteur FROM pictures WHERE validate = '1' ORDER BY name DESC;";
    $nbr = 0;
    foreach ($pdo->query($sql) as $row)
    {
		if ($row["auteur"] == $usr)
			$nbr++;
    }
    $nbr = $nbr / 12;
    $nbr = ceil($nbr);
    return ($nbr);
}

function ft_delete_pic($pdo, $name)
{
	$sth = $pdo->prepare("UPDATE pictures SET validate = '0' WHERE name = ?");
	$sth->bindParam(1, $name, PDO::PARAM_STR);
	$sth->execute();
}

function ft_send_mail_to($pdo, $tmp)
{
	$usr = NULL;
	$mail = NULL;
	$sql = "SELECT name, auteur FROM pictures WHERE validate = '1';";
	foreach ($pdo->query($sql) as $row)
    {
        if ($row["name"] == $tmp['name'])
            $usr = $row["auteur"];
    }
	$sql = 'SELECT email, login FROM users;';
    foreach ($pdo->query($sql) as $row)
    {
		if ($row["login"] === $usr)
            $mail = $row["email"];
    }
	$link = 'http://localhost:8080/PROJET_9_CAMAGRU/picture.php?name='.$tmp['name'];
	$msg = 'Hi '.$usr.'! '.$tmp['auteur'].' has comment one of your pictures. Read it at '.$link.'!';
	mail($mail, "Comments", $msg);
}

function ft_valid_mail($mail)
{
	$ret = preg_match("/.+@.+\..+/", $mail);
	return ($ret);
}
		
?>