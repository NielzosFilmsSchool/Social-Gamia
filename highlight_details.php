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
    <script src="https://kit.fontawesome.com/82664ff85a.js" crossorigin="anonymous"></script>
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
        <a href="profile.php?user=<?= $_COOKIE["loggedInUser"]?>">
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
                    if($row["created_user_id"] == $_COOKIE["loggedInUser"]){
                        ?>
                        <a class="edit_community" href="community_edit.php?community_id=<?= $_GET["community_id"] ?>">Edit community</a>
                        <?php
                    }
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

    </header></center>

    <center><main class="community_header">

    <?php
        try {
            $stmt = $pdo->query('SELECT * FROM highlight_posts WHERE id = '.$_GET["id"]);
            if($stmt->rowCount() == 0) {
                throw new Exception("No highlight found!");
            }

            ?>
            <div>
            <?php
            
            while($row = $stmt->fetch()) {
                $user_query = $pdo->query('SELECT * FROM users WHERE id = '.$row["user_id"]);
                $user = $user_query->fetch();

                $time_input = strtotime($row["post_date"]);
                $date = date("d-M-Y", $time_input);
                $time = date("H:i:s", $time_input);
                ?>
                <div class="highlight">
                    <h2> <?= $row["caption"] ?> </h2>
                    <label><?= $user["username"]?></label>
                    <p><?= $row["description"]?></p>
                    <label class="highlight_date"><?= $date?></label>
                    <label class="highlight_date" style="top: 60px;"><?= $time?></label>
                    <img class="highlight_img" src="IMG/img-test.jpg" alt="Photo">
                    <label class="highlight_likes"><?= $row["likes"] ?> Likes</label>
                    <?php
                    if($row["user_id"] == $_COOKIE["loggedInUser"]) {
                        ?>
                        <form action="highlight_edit.php?community_id=<?= $_GET["community_id"]?>&id=<?= $_GET["id"]?>"  method="post">
                            <input type="submit" value="Edit highlight">
                        </form>
                        <br>
                        <br>
                        <form action="highlight_delete.php?community_id=<?= $_GET["community_id"]?>&id=<?= $_GET["id"]?>" method="post">
                            <input class="danger" type="submit" value="Delete highlight">
                        </form>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }

            ?>
            </div>
            <?php

        } catch(Exception $e) {
            echo "<h3>$e</h3>";
        }
        ?>

    </main></center>
    
    <footer></footer>

</body>
</html>