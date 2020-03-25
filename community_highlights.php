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
    <link href="https://fonts.googleapis.com/css?family=Fredoka+One&display=swap" rel="stylesheet">
</head>

<body>
    <div id="menu"> 
        <a href="index.php">
            <div class="tooltip">
                <i class="fas fa-home"></i>
                <span class="tooltiptext">Home</span>
            </div>
        </a>
        <a href="communities.php">
            <div class="tooltip">
                <i class="fas fa-satellite-dish"></i>
                <span class="tooltiptext">Communities</span>
            </div>
        </a>
        <a href="profile.php">
            <div class="tooltip">
                <i class="fas fa-user"></i>
                <span class="tooltiptext">Profile</span>
            </div>
        </a>
        <a href="community_create.php">
            <div class="tooltip">
            <i class="fas fa-plus"></i>
                <span class="tooltiptext">Create Community</span>
            </div>
        </a>
    </div>
    
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
                <?php
                $stmt_user = $pdo->query('SELECT * FROM users WHERE id = '.$row["created_user_id"]);
                while($row_user = $stmt_user->fetch()) {
                    ?>
                    <label>Created by <?= $row_user["username"] ?></label>
                    <?php
                }
                ?>
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
<script src="https://kit.fontawesome.com/82664ff85a.js" crossorigin="anonymous"></script>
</html>