<?php
$dsn = "mysql:host=localhost;dbname=social_gamia";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Social Gamia | Home</title>
    <link rel="stylesheet" type="text/css" href="CSS/theme.css">
    <script src="https://kit.fontawesome.com/82664ff85a.js" crossorigin="anonymous"></script>
</head>

<body>

    <header>
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
    </header>

    <center><main class="home_main">
        <div class="communities_container">
            <?php
            try {
                $stmt = $pdo->query('SELECT * FROM communities ORDER BY name ASC');
                if($stmt->rowCount() == 0) {
                    throw new Exception("No communities found!");
                }
                if (!isset($_COOKIE['loggedInUser'])) {
                    throw new Exception("U bent niet ingelogd, u wordt nu doorgestuurd naar de login pagina.");
                }
                ?>
                <table class="communities_table">
                    <tr>
                        <th>Communities</th>
                    </tr>
                <?php
                
                while($row = $stmt->fetch()) {
                    ?>
                    <tr>
                        <td>
                            <a href="community_highlights.php?community_id=<?= $row["id"] ?>"> <?= $row["name"] ?> </a>
                        </td>
                    </tr>
                    <?php
                }

                ?>
                </table>
                <?php

            } catch(Exception $e) {
                echo "<h3>".$e->getMessage()."</h3>";
                if ($e->getMessage() == "U bent niet ingelogd, u wordt nu doorgestuurd naar de login pagina.") {
                    echo "<script>setTimeout(\"location.href = 'logout.php';\",1500);</script>";
                }
            }
            ?>
        </div>

        <div class="feed_container">
            <?php
            try {
                // $stmt = $pdo->query('SELECT * FROM communities ORDER BY name ASC');
                // if($stmt->rowCount() == 0) {
                //     throw new Exception("No communities found!");
                // }

                ?>
                <table class="feed_table">
                    <tr>
                        <th>Feed</th>
                    </tr>
                <?php
                
                //while($row = $stmt->fetch()) {
                    ?>
                    <tr>
                        <td>
                            Feed item
                        </td>
                    </tr>
                    <?php
                //}

                ?>
                </table>
                <?php

            } catch(Exception $e) {
                echo "<h3>".$e->getMessage()."</h3>";
            }
            ?>
        </div>
    </main></center>
        
    <footer></footer>
        
</body>
    
</html>