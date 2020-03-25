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
                "INSERT INTO communities (name, description)
                VALUES ('".$_POST["name"]."', '".$_POST["desc"]."')"
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
        <title>Document</title>
    </head>
    <body>
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
