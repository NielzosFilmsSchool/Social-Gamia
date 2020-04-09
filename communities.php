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
    <title>Social Gamia | Communities</title>
    <link rel="stylesheet" type="text/css" href="CSS/theme.css">
    <script src="https://kit.fontawesome.com/82664ff85a.js" crossorigin="anonymous"></script>
   
</head>

<body class="comm_body">


<div id="position_log">
    <div class="logout_btn">
    <a href="logout.php">Logout</a>
    </div>
</div>

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
    
        <div class="search-button">
            <h1>Search a Community</h1>
            <form action="communities.php" method="GET">
                <input type="text" placeholder="Search communities..." name="search" >
                <input type="submit" name="submit_search" value="Search">
            </form>
    </div>

        <div id="create-btn">
        <a href="community_create.php"><button class="create-com">
            <span><i class="fa fa-plus-square-o" aria-hidden="true"></i></span>
            Create a Community
        </button></a>

    </div> 
      

    </header>

    <main>
        
        <table class="communities_table">
            <tr>
                <th>
                    Communities
                </th>
            </tr>

            <?php
            try {
                $stmt = $pdo->query('SELECT * FROM communities ORDER BY name ASC');
                if(isset($_GET["submit_search"])) {
                    if(!empty($_GET["search"])) {
                        $stmt = $pdo->query('SELECT * FROM communities WHERE name like "%'.$_GET["search"].'%" ORDER BY name ASC');
                    }
                }
                if($stmt->rowCount() == 0) {
                    throw new Exception("No communities found!");
                }
                while($row = $stmt->fetch()) {
                    ?>
                    <tr>
                        <td>
                            <a href="community_highlights.php?community_id=<?= $row["id"] ?>"> <?= $row["name"] ?> </a>
                        </td>
                    </tr>
                    <?php
                }
            } catch (Exception $e) {
                echo "<h3>$e</h3>";
            }

            ?>
        </table>

    </main>



 
    
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
?>



<?php
        /*try {
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
        }*/
        ?>