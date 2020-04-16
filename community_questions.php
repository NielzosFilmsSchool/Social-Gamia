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
        <a href="direct_messages.php">
                <div class="tooltip">
                <i class="fas fa-paper-plane"></i>
                    <span class="tooltiptext">Messages</span>
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
        <div class="question_controls border_bottom padding" style="width:60%;">
            <form action="community_questions.php?community_id=<?= $_GET["community_id"]?>" id="question_form" method="post">
                <textarea name="question" form="question_form"></textarea>
                <input class="green" type="submit" name="submit_question" value="Submit question">
            </form>
        </div>
        <?php
        try {
            $stmt = $pdo->query('SELECT * FROM question_posts WHERE community_id = '.$_GET["community_id"].' ORDER BY question_date DESC');
            if($stmt->rowCount() == 0) {
                throw new Exception("No questions found!");
            }

            ?>
            <div class="questions_container">
            <?php
            
            while($row = $stmt->fetch()) {
                $user_query = $pdo->query("SELECT * FROM users WHERE id = ".$row["user_id"]);
                $user = $user_query->fetch();
                ?>
                <div class="question border_bottom">
                    <p> <?= $row["question"] ?> </p>
                    <a href="profile.php?user=<?= $user["id"]?>"><?= $user["username"]?></a><br>
                    <a href="question_details.php?community_id=<?= $_GET["community_id"]?>&id=<?= $row["id"]?>">Details</a><br>
                    <label><?= $row["question_date"] ?></label>
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

if(isset($_POST["submit_question"]) && !empty($_POST["question"])){
    $date = date("Y-m-d H:i:s");
    $sql = $pdo->prepare(
        "INSERT INTO question_posts (question, user_id, community_id, question_date)
        VALUES ('".$_POST["question"]."', ".$_COOKIE["loggedInUser"].", ".$_GET["community_id"].", '$date')"
    );
    $sql->execute();
}
?>