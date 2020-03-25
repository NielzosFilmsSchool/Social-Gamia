<?php
$dsn = "mysql:host=localhost;dbname=social_gamia";
$user = "root";
$passwd = "";

$pdo = new PDO($dsn, $user, $passwd);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="CSS/theme.css">
        <script src="https://kit.fontawesome.com/82664ff85a.js" crossorigin="anonymous"></script>
        <title>Document</title>
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

        <h1>Edit Community</h1>
        <form action="community_edit.php?community_id=<?= $_GET["community_id"]?>" method="post">
            <?php
            try {
                $stmt = $pdo->query('SELECT * FROM communities WHERE id = '.$_GET["community_id"]);
                if($stmt->rowCount() == 0) {
                    throw new Exception("No communities found!");
                }
                
                while($row = $stmt->fetch()) {
                    ?>
                    <h1> <?= $row["name"] ?> </h1>
                    <?php
                    $stmt_user = $pdo->query('SELECT * FROM users WHERE id = '.$row["created_user_id"]);
                    while($row_user = $stmt_user->fetch()) {
                        ?>
                        <input type="text" name="name" placeholder="Community name..." value="<?= $row["name"]?>">
                        <input type="text" name="desc" placeholder="Community description..." value="<?= $row["description"]?>">
                        <input type="submit" name="submit" value="Save">
                        <?php
                        if($row["created_user_id"] != $_COOKIE["loggedInUser"]){
                            header('Location: community_highlights.php?community_id='.$_GET["community_id"]);
                        }
                    }
                }
    
            } catch(Exception $e) {
                echo "<h3>$e</h3>";
            }
            ?>
        </form>
        <br>
        <br>
        <br>
        <br>
        <br>
        <form action="community_delete.php?community_id=<?= $_GET["community_id"]?>" method="POST">
            <input type="submit" class="danger" value="Delete this community">
        </form>
        
    </body>
</html>

<?php
try {
    if (isset($_POST['submit'])) {
        if(isset($_POST['name']) && isset($_POST['desc'])){
            $stmt = $pdo->prepare(
                "UPDATE communities
                SET name = '".$_POST["name"]."', description = '".$_POST["desc"]."'
                WHERE id = ".$_GET["community_id"]
            );
            $stmt->execute();
            header('Location: community_highlights.php?community_id='.$_GET["community_id"]);//redirect to community details
        }else {
            throw new Exception("Je hebt niet alles ingevult.");
        }
    }
} catch (Exception $e) {
    echo '<h1>' . $e->getMessage() . '</h1>';
}
?>

