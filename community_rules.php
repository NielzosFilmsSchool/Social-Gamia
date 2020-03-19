<?php
$dsn = "mysql:host=localhost;dbname=social_gamia";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);
?>

<!DOCTYPE html>
<html>

<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="CSS/theme.css">
</head>

<body>

    <center><header class="community_header">
        <?php
        try {
            $stmt = $pdo->query('SELECT * FROM communities WHERE id = '.$_GET["community_id"]);
            if($stmt->rowCount() == 0) {
                throw new Exception("No communities found!");
            }

            ?>
            <div>
            <?php
            
            while($row = $stmt->fetch()) {
                ?>
                <h1> <?= $row["name"] ?> </h1>
                <p> <?= $row["description"]?> </p>
                <?php
            }

            ?>
            </div>
            <?php

        } catch(Exception $e) {
            echo "<h3>$e</h3>";
        }
        ?>
        <br>
        <div id='menu_2'>
            <a href="community_highlights.php?community_id=<?= $_GET["community_id"] ?>">Highlights</a>
            <a href="community_questions.php?community_id=<?= $_GET["community_id"] ?>">Questions</a>
            <a href="community_rules.php?community_id=<?= $_GET["community_id"] ?>">Rules</a>
        </div>
    </header></center>

    <main></main>
    
    <footer></footer>

</body>

</html>