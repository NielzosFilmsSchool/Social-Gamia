<?php
function showingProfile() 
{
$dsn = "mysql:host=localhost;dbname=social_gamia";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$_COOKIE['loggedInUser']]);
?>
<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="CSS/theme.css">
</head>

<body>

    <header></header>

    <center><main>
        <?php
        try {
            if($stmt->rowCount() == 0) {
                throw new Exception("No information found!");
            }

            ?>
            <div>
            <?php
            
            while($row = $stmt->fetch()) {
                ?>
                <div>
                    <h3><?= $row["username"] ?></h3>
                    <h4><?= $row["email"] ?></h4>
                    <h4><?= $row["phone"] ?></h4>
                    <h4><?= $row["posts_count"] ?></h4>
                    <h4><?= $row["comment_count"] ?></h4>
                    <h4><?= $row["question_count"] ?></h4>
                    <h4><?= $row["following_communities"] ?></h4>
                    <h4><?= $row["following_users"] ?></h4>
                </div>
                <?php
            }

            ?>
            </div>
            <?php

        } catch(Exception $e) {
            echo "<h3>".$e->getMessage()."</h3>";
        }
        ?>
    </main></center>
    
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