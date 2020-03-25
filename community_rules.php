<?php
$dsn = "mysql:host=localhost;dbname=social_gamia";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);
?>

<!DOCTYPE html>

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

    <main class="community_main">
        <form action="community_rules.php?community_id=<?= $_GET["community_id"] ?>" method="POST">
            <input type="text" name="rule" placeholder="Rule...">
            <input type="submit" name="add_rule" value="Add Rule">
        </form>

        <?php
        try {
            $stmt = $pdo->query('SELECT rules FROM communities WHERE id = '.$_GET["community_id"]);
            if($stmt->rowCount() == 0) {
                throw new Exception("No rules found!");
            }
            ?>
            <table class="rules_table">
            <?php
            while($row = $stmt->fetch()) {
                if($row["rules"] == null) {
                    break;
                }
                foreach(explode("&;", $row["rules"]) as $rule) {
                    ?>
                    <tr>
                        <td>
                            <?= $rule ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
            </table>
            <?php

        } catch(Exception $e) {
            echo "<h3>$e</h3>";
        }
        ?>

    </main>
    
    <footer></footer>

</body>
</html>

<?php
try {
    if (isset($_POST['add_rule'])) {
        if(isset($_POST['rule'])){
            $stmt_community = $pdo->query('SELECT rules FROM communities WHERE id = '.$_GET["community_id"]);
            if($stmt_community->rowCount() == 0) {
                throw new Exception("No rules found!");
            }
            $rules = array();

            while($row = $stmt_community->fetch()) {
                if($row["rules"] == null) {
                    break;
                }
                $rules = explode("&;", $row["rules"]);
            }

            array_push($rules, $_POST["rule"]);

            $rules_str = implode("&;", $rules);

            $stmt = $pdo->prepare(
                "UPDATE communities
                SET rules = '$rules_str'
                WHERE id = ".$_GET["community_id"]
            );
            $stmt->execute();
            header("Refresh:0");
        }else {
            throw new Exception("Je hebt niet alles ingevult.");
        }
    }
} catch (Exception $e) {
    echo "<label>$e</label>";
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