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
    </header></center>

    <center><main>
        <?php
        try {
            $stmt = $pdo->query('SELECT * FROM highlight_posts WHERE community_id = '.$_GET["community_id"]);
            if($stmt->rowCount() == 0) {
                throw new Exception("No highlights found!");
            }

            ?>
            <div class="highlights_container">
            <?php
            
            while($row = $stmt->fetch()) {
                ?>
                <div class="highlight">
                    <h2> <?= $row["caption"] ?> </h2>
                    <label><?= $row["post_date"] ?></label>
                    <!--images-->
                    <label><?= $row["likes"] ?></label>
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