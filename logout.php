<?PHP

include("auth.php");

if (session_start() == true)
{
   $_SESSION['loggued_on_user'] = NULL;
   echo '<script>alert("You had been logout!");';
   echo 'location.href = "index.php";</script>';
}

?>
