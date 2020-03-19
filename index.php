<?php
$dsn = "mysql:host=localhost;dbname=social_gamia";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="CSS/theme.css">
</head>

<body>

    <header></header>

    <main>
        <div id="menu">
            <a href="index.php"><i class="fas fa-home"></i></a>
            <a href=""><i class="fas fa-satellite-dish"></i></a>
            <a href=""><i class="fas fa-user"></i></a>
            <a href="community_create.php"><i class="fas fa-plus"></i></a>

        </div>

        <div class="communities_container">
            <?php
            try {
                $stmt = $pdo->query('SELECT * FROM communities ORDER BY name ASC');
                if($stmt->rowCount() == 0) {
                    throw new Exception("No communities found!");
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
                            <a href="community.php?community_id=<?= $row["id"] ?>"> <?= $row["name"] ?> </a>
                        </td>
                    </tr>
                    <?php
                }

                ?>
                </table>
                <?php

            } catch(Exception $e) {
                echo "<h3>$e</h3>";
            }
            ?>
        </div>
    </main>
        
    <footer></footer>
        
</body>
<script src="https://kit.fontawesome.com/82664ff85a.js" crossorigin="anonymous"></script>
    
</html>