<?php   
setcookie('loggedInUser', "", time() - 300);
sleep(2);
header("Location: /PHP/login.php")
?>