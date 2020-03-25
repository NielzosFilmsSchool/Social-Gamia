<?php
function createCommunity() 
{
    $dsn = "mysql:host=localhost;dbname=social_gamia";
    $user = "root";
    $passwd = "";

    $pdo = new PDO($dsn, $user, $passwd);
    if (isset($_POST['submit'])) {
        if(isset($_POST['name']) && isset($_POST['desc'])){
            $stmt = $pdo->prepare(
                "INSERT INTO communities (name, description, created_user_id)
                VALUES ('".$_POST["name"]."', '".$_POST["desc"]."', ".$_COOKIE["loggedInUser"].")"
            );
            $stmt->execute();
            header('Location: index.php');//redirect to community details
        }else {
            throw new Exception("Je hebt niet alles ingevult.");
        }
    }
}
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

        <h1>Create Community</h1>
        <form action="community_create.php" method="post">
            <input type="text" name="name" placeholder="Community name...">
            <input type="text" name="desc" id="" placeholder="Community description...">
            <input type="submit" name="submit">
        </form>

        
    </body>
</html>

<?php
try {
    createCommunity();
} catch (Exception $e) {
    echo '<h1>' . $e->getMessage() . '</h1>';
}
?>

