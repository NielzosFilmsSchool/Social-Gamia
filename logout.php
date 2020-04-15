<?php   
if(!isset($_COOKIE["loggedInUser"])) {
    header('Location: login.php');
}
setcookie('loggedInUser', "", time() - 300);
sleep(2);
header("Location: login.php")
?>