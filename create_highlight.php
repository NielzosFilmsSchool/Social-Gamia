<?php
$dsn = "mysql:host=localhost;dbname=social_gamia";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);

if(!isset($_COOKIE["loggedInUser"])) {
    header('Location: login.php');
}
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

        <form id="create_highlight" action="create_highlight.php?community_id=<?= $_GET["community_id"] ?>" method="post">
            <input type="text" name="caption" placeholder="Caption..."><br>
            <textarea name="desc" form="create_highlight" cols="30" rows="10" placeholder="Description..."></textarea><br>
            <input type="submit" name="submit" value="Create highlight">
        </form>

    </main></center>
    
    <footer></footer>

</body>
</html>

<?php
if(isset($_POST["submit"])){
    if(!empty($_POST["caption"]) && !empty($_POST["desc"])){
        $date = date("Y-m-d H:i:s");
        $stmt = $pdo->prepare(
            "INSERT INTO highlight_posts (user_id, caption, description, likes, community_id, post_date)
            VALUES ('".$_COOKIE["loggedInUser"]."', '".$_POST["caption"]."', '".$_POST["desc"]."', 0, '".$_GET["community_id"]."', '$date')"
        );
        $stmt->execute();
        header('Location: community_highlights.php?community_id='.$_GET["community_id"]);
    }
}
?>