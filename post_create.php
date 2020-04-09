<?php
if(!isset($_COOKIE["loggedInUser"])) {
    header('Location: login.php');
}

function showingProfile() 
{
?>
<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="CSS/theme.css">
</head>

<body>

<div id="position_log">
    <div class="logout_btn">
    <a href="logout.php">Logout</a>
    </div>
    </div>

    <header></header>

    <main></main>
    
    <footer></footer>

</body>

</html>
<?php
}
try {
    showingProfile();
} catch (Exception $e) {
    echo "<h3>".$e->getMessage()."</h3>";
}
try {
    if (!isset($_COOKIE['loggedInUser'])) {
        throw new Exception("U bent niet ingelogd, u wordt nu doorgestuurd naar de login pagina.");
    }
} catch (Exception $e) {
    echo "<h3>".$e->getMessage()."</h3>";
    if ($e->getMessage() == "U bent niet ingelogd, u wordt nu doorgestuurd naar de login pagina.") {
        echo "<script>setTimeout(\"location.href = 'logout.php';\",1500);</script>";
    }
}
?>